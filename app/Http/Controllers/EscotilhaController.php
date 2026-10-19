<?php

namespace App\Http\Controllers;

use App\Models\Escotilha;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\ApiResponse;
use App\Services\MqttService;

class EscotilhaController extends Controller
{
    private const OFFLINE_APOS_S = 60;

    public function toggleComporta(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string',
            'action'        => 'sometimes|in:liberar,abrir',
        ]);

        $escotilha = Escotilha::where('serial_number', $request->serial_number)->first();

        if (!$escotilha) {
            return ApiResponse::error('Escotilha não encontrada', 404);
        }

        if ($escotilha->user_id && $escotilha->user_id !== $request->user()->id) {
            return ApiResponse::error('Você não tem permissão para liberar esta escotilha', 403);
        }

        $topic   = "escotilha/{$escotilha->serial_number}/comando";
        $message = json_encode(['action' => 'liberar']);

        MqttService::publish($topic, $message);

        return ApiResponse::success([
            'message' => "Comando de liberação enviado para {$escotilha->serial_number}",
        ]);
    }

    public function estado(Request $request, string $serial)
    {
        $escotilha = Escotilha::where('serial_number', $serial)->first();

        if (!$escotilha) {
            return ApiResponse::error('Escotilha não encontrada', 404);
        }

        if ($escotilha->user_id && $escotilha->user_id !== $request->user()->id) {
            return ApiResponse::error('Você não tem permissão para ver esta escotilha', 403);
        }

        $agora        = now();
        $ultimaLeitura = $escotilha->ultima_leitura_em ? Carbon::parse($escotilha->ultima_leitura_em) : null;
        $liberadoAte  = $escotilha->liberado_ate ? Carbon::parse($escotilha->liberado_ate) : null;

        $online   = $ultimaLeitura && $ultimaLeitura->diffInSeconds($agora, true) <= self::OFFLINE_APOS_S;
        $restante = ($liberadoAte && $liberadoAte->greaterThan($agora))
            ? (int) $agora->diffInSeconds($liberadoAte, true)
            : 0;

        if (!$online) {
            $estado = 'offline';
        } elseif ($escotilha->travado) {
            $estado = 'travado';
        } elseif ($restante > 0) {
            $estado = 'liberado';
        } else {
            $estado = 'protegido';
        }

        return ApiResponse::success([
            'serial_number'       => $escotilha->serial_number,
            'estado'              => $estado,
            'travado'             => (bool) $escotilha->travado,
            'liberado_restante_s' => $restante,
            'ultima_leitura_em'   => $ultimaLeitura?->toIso8601String(),
        ]);
    }

    public function listarEscotilhas()
    {
        $escotilhas = Escotilha::all();
        return ApiResponse::success($escotilhas);
    }

    public function obterPorId($id)
    {
        $escotilha = Escotilha::find($id);

        if (!$escotilha) {
            return ApiResponse::error('Escotilha não encontrada', 404);
        }

        return ApiResponse::success($escotilha);
    }

    public function cadastrar(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string|unique:escotilhas,serial_number',
        ]);

        $escotilha = Escotilha::create([
            'serial_number' => $request->serial_number,
        ]);

        return ApiResponse::success($escotilha, 201);
    }

    public function atualizar(Request $request, $id)
    {
        $escotilha = Escotilha::find($id);

        if (!$escotilha) {
            return ApiResponse::error('Escotilha não encontrada', 404);
        }

        $request->validate([
            'serial_number' => 'required|string|unique:escotilhas,serial_number,' . $id,
        ]);

        $escotilha->update([
            'serial_number' => $request->serial_number,
        ]);

        return ApiResponse::success($escotilha);
    }

    public function deletar($id)
    {
        $escotilha = Escotilha::find($id);

        if (!$escotilha) {
            return ApiResponse::error('Escotilha não encontrada', 404);
        }

        $escotilha->delete();

        return ApiResponse::success(['message' => 'Escotilha deletada com sucesso']);
    }
}
