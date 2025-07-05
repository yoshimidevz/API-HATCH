<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Escotilha;
use Carbon\Carbon;
use App\Services\ApiResponse;

class EscotilhaController extends Controller
{
    public function inserirEscotilha(Request $request)
    {
        try {
            $escotilha = Escotilha::create([
                'distancia' => $request->distancia,
                'luz_ambiente' => $request->luz_ambiente,
                'hora_atualizacao' => Carbon::now(),
            ]);

            return ApiResponse::success([
                'message' => 'Registro da escotilha inserido com sucesso',
                'data' => $escotilha
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao inserir registro da escotilha: ' . $e->getMessage());
            return ApiResponse::error('Erro ao inserir registro da escotilha');
        }
    }

    public function atualizarEscotilha(Request $request, $id)
    {
        $request->validate([
            'distancia' => 'required|numeric',
            'luz_ambiente' => 'required|numeric',
        ]);

        try {
            $escotilha = Escotilha::find($id);

            if (!$escotilha) {
                return ApiResponse::error('Registro da escotilha não encontrado');
            }

            $escotilha->update([
                'distancia' => $request->distancia,
                'luz_ambiente' => $request->luz_ambiente,
                'hora_atualizacao' => Carbon::now(),
            ]);

            return ApiResponse::success([
                'message' => 'Registro da escotilha atualizado com sucesso',
                'data' => $escotilha
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar registro da escotilha: ' . $e->getMessage());
            return ApiResponse::error('Erro ao atualizar registro da escotilha');
        }
    }

    public function listarEscotilha()
    {
        try {
            $registros = Escotilha::orderBy('hora_atualizacao', 'desc')->get();
            return ApiResponse::success([
                'message' => 'Lista de registros da escotilha',
                'data' => $registros
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao listar registros da escotilha: ' . $e->getMessage());
            return ApiResponse::error('Erro ao listar registros da escotilha');
        }
    }

    public function obterEscotilhaPorId($id)
    {
        try {
            $escotilha = Escotilha::find($id);

            if (!$escotilha) {
                return ApiResponse::error('Registro da escotilha não encontrado');
            }

            return Apiresponse::success([
                'message' => 'Registro da escotilha encontrado',
                'data' => $escotilha
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao obter registro da escotilha: ' . $e->getMessage());
            return ApiResponse::error('Erro ao obter registro da escotilha');
        }
    }

    public function deletarEscotilha($id)
    {
        try {
            $escotilha = Escotilha::find($id);

            if (!$escotilha) {
                return ApiResponse::error('Registro da escotilha não encontrado');
            }

            $escotilha->delete();

            return ApiResponse::success([
                'message' => 'Registro da escotilha excluído com sucesso',
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao excluir registro da escotilha: ' . $e->getMessage());
            return ApiResponse::error('Erro ao excluir registro da escotilha');
        }
    }
}
