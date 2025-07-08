<?php

namespace App\Http\Controllers;

use App\Models\Escotilha;
use Illuminate\Http\Request;
use App\Services\ApiResponse;

class EscotilhaController extends Controller
{
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
