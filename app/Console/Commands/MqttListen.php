<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class MqttListen extends Command
{
    protected $signature = 'mqtt:listen';
    protected $description = 'Escuta as leituras das escotilhas via MQTT, salva no banco e gera alertas';

    // [AJUSTAR] use os mesmos valores de "type" que aparecem no passo 29a
    private const TIPO_CRITICO = 'critical';
    private const TIPO_INFO    = 'info';

    private const TOPICO = 'escotilha/+/sensor';
    private const INTERVALO_SALVAR_S = 60;

    /** Estado anterior de cada escotilha: [serial => ['vazamento' => bool, 'solenoide' => string, 'ultimoSalvo' => int]] */
    private array $estado = [];

    private ?MqttClient $mqtt = null;

    public function handle(): int
    {
        $this->mqtt = new MqttClient(
            config('mqtt.host'),
            (int) config('mqtt.port'),
            'laravel-smarthatch-listener',
            MqttClient::MQTT_3_1_1
        );

        $settings = (new ConnectionSettings)
            ->setUseTls(false)
            ->setUsername(config('mqtt.username'))
            ->setPassword(config('mqtt.password'))
            ->setKeepAliveInterval(60)
            ->setReconnectAutomatically(true)
            ->setMaxReconnectAttempts(100)
            ->setDelayBetweenReconnectAttempts(5000);

        if (function_exists('pcntl_signal')) {
            pcntl_async_signals(true);
            pcntl_signal(SIGTERM, fn () => $this->mqtt->interrupt());
            pcntl_signal(SIGINT,  fn () => $this->mqtt->interrupt());
        }

        $this->mqtt->connect($settings, false);
        $this->info('[MQTT] Conectado. Escutando ' . self::TOPICO);

        $this->mqtt->subscribe(self::TOPICO, function (string $topic, string $message) {
            $this->processar($topic, $message);
        }, 0);

        $this->mqtt->loop(true);
        $this->mqtt->disconnect();
        $this->info('[MQTT] Encerrado.');

        return self::SUCCESS;
    }

    private function processar(string $topic, string $message): void
    {
        $dados = json_decode($message, true);
        if (!is_array($dados) || empty($dados['serial_number'])) {
            $this->warn("[MQTT] Mensagem ignorada em {$topic}: {$message}");
            return;
        }

        $serial    = $dados['serial_number'];
        $vazamento = (bool) ($dados['vazamento'] ?? false);
        $solenoide = (string) ($dados['solenoide'] ?? 'desconhecido');

        $anterior = $this->estado[$serial] ?? [
            'vazamento'   => false,
            'solenoide'   => null,
            'ultimoSalvo' => 0,
        ];

        $mudouVazamento = $vazamento !== $anterior['vazamento'];
        $mudouSolenoide = $anterior['solenoide'] !== null && $solenoide !== $anterior['solenoide'];
        $hora           = time() - $anterior['ultimoSalvo'] >= self::INTERVALO_SALVAR_S;

        if (!$mudouVazamento && !$mudouSolenoide && !$hora) {
            $this->estado[$serial] = array_merge($anterior, ['solenoide' => $solenoide]);
            return;
        }

        $this->comBanco(function () use ($serial, $dados, $vazamento, $solenoide, $mudouVazamento, $mudouSolenoide) {
            $escotilha = DB::table('escotilhas')
                ->where('serial_number', $serial)
                ->whereNull('deleted_at')
                ->first();

            if (!$escotilha) {
                $this->warn("[MQTT] Escotilha {$serial} nao cadastrada, leitura ignorada");
                return;
            }

            $agora = now();

            $leituraId = DB::table('sensor_data')->insertGetId([
                'escotilha_id'     => $escotilha->id,
                'distancia'        => $dados['distancia_cm'] ?? null,
                'luz_ambiente'     => $dados['lux'] ?? null,
                'hora_atualizacao' => $agora,
                'created_at'       => $agora,
                'updated_at'       => $agora,
            ]);

            if ($mudouVazamento && $vazamento) {
                $this->criarAlerta($escotilha->id, $leituraId, self::TIPO_CRITICO,
                    'Vazamento detectado! A bica foi fechada automaticamente.');
            }

            if ($mudouSolenoide) {
                $texto = $solenoide === 'fechado' ? 'Bica fechada.' : 'Bica aberta.';
                $this->criarAlerta($escotilha->id, $leituraId, self::TIPO_INFO, $texto);
            }

            $this->line(sprintf('[%s] %s | lux %.1f | dist %.1f | vazamento %s | bica %s',
                $agora->format('H:i:s'), $serial, $dados['lux'] ?? 0, $dados['distancia_cm'] ?? 0,
                $vazamento ? 'SIM' : 'NAO', $solenoide));
        });

        $this->estado[$serial] = [
            'vazamento'   => $vazamento,
            'solenoide'   => $solenoide,
            'ultimoSalvo' => time(),
        ];
    }

    private function criarAlerta(int $escotilhaId, int $leituraId, string $tipo, string $mensagem): void
    {
        $agora = now();
        DB::table('alertas')->insert([
            'escotilha_id'   => $escotilhaId,
            'sensor_data_id' => $leituraId,
            'type'           => $tipo,
            'message'        => $mensagem,
            'created_at'     => $agora,
            'updated_at'     => $agora,
        ]);
        $this->info("[ALERTA] {$tipo}: {$mensagem}");
    }

    /** Executa a operacao no banco e reconecta uma vez se o MySQL tiver derrubado a conexao */
    private function comBanco(callable $operacao): void
    {
        try {
            $operacao();
        } catch (QueryException $e) {
            Log::warning('[MQTT] Erro no banco, reconectando: ' . $e->getMessage());
            DB::reconnect();
            try {
                $operacao();
            } catch (\Throwable $e) {
                Log::error('[MQTT] Falha ao salvar leitura: ' . $e->getMessage());
                $this->error('[MQTT] Falha ao salvar: ' . $e->getMessage());
            }
        }
    }
}
