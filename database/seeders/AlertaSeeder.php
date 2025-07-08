<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alerta;
use App\Models\Escotilha;
use App\Models\SensorData;
use App\Models\Sensor;

class AlertaSeeder extends Seeder
{
    public function run()
    {
        $escotilhaId = Escotilha::inRandomOrder()->value('id'); // Obtém um ID aleatório de escotilha
        $sensorDataId = SensorData::inRandomOrder()->value('id');
        $sensorId = Sensor::inRandomOrder()->value('id');

        if (!$escotilhaId || !$sensorDataId || !$sensorId) {
            $this->command->info('Faltam dados para criar alertas. Crie escotilhas, sensores e sensor_data antes.');
            return;
        }

        Alerta::create([
            'escotilha_id' => $escotilhaId,
            'sensor_data_id' => $sensorDataId,
            'type' => 'ABERTURA',
            'message' => 'Alerta simulado: escotilha aberta inesperadamente.',
        ]);

        Alerta::create([
            'escotilha_id' => $escotilhaId,
            'sensor_data_id' => $sensorDataId,
            'type' => 'ERRO_SENSOR',
            'message' => 'Alerta simulado: falha na leitura do sensor.',
        ]);

        $this->command->info('Alertas simulados criados com sucesso!');
    }
}
