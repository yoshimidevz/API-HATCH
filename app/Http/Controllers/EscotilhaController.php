<?php

namespace App\Http\Controllers;

use App\Models\Escotilha;
use Illuminate\Http\Request;
use App\Services\ApiResponse;

class EscotilhaController extends Controller
{
    public function toggleComporta(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string',
            'action' => 'required|in:abrir,fechar'
        ]);

        $serial = $request->serial_number;
        $action = $request->action;

        // Aqui você envia o comando pro ESP32
        // Pode ser HTTP, MQTT ou outro método que você já use
        $result = \App\Services\EspSenderService::send($serial, $action);

        if ($result) {
            return ApiResponse::success([
                'message' => "Comando {$action} enviado para {$serial}"
            ]);
        } else {
            return ApiResponse::error("Falha ao enviar comando", 500);
        }
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
