<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Escotilha;
use Carbon\Carbon;

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

            return response()->json([
                'message' => 'Registro inserido com sucesso',
                'data' => $escotilha
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Erro ao inserir registro da escotilha: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao inserir registro da escotilha'], 500);
        }
    }

    public function atualizarEscotilha(Request $request, $id)
    {
        try {
            $escotilha = Escotilha::find($id);

            if (!$escotilha) {
                return response()->json(['message' => 'Registro da escotilha não encontrado'], 404);
            }

            $escotilha->update([
                'distancia' => $request->distancia,
                'luz_ambiente' => $request->luz_ambiente,
                'hora_atualizacao' => Carbon::now(),
            ]);

            return response()->json([
                'message' => 'Registro da escotilha atualizado com sucesso',
                'data' => $escotilha
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar registro da escotilha: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao atualizar registro da escotilha'], 500);
        }
    }

    public function listarEscotilha()
    {
        try {
            $registros = Escotilha::orderBy('hora_atualizacao', 'desc')->get();
            return response()->json(['data' => $registros]);
        } catch (\Exception $e) {
            \Log::error('Erro ao listar registros da escotilha: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao listar registros da escotilha'], 500);
        }
    }

    public function obterEscotilhaPorId($id)
    {
        try {
            $escotilha = Escotilha::find($id);

            if (!$escotilha) {
                return response()->json(['message' => 'Registro da escotilha não encontrado'], 404);
            }

            return response()->json(['data' => $escotilha]);
        } catch (\Exception $e) {
            \Log::error('Erro ao obter registro da escotilha: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao obter registro da escotilha'], 500);
        }
    }

    public function deletarEscotilha($id)
    {
        try {
            $escotilha = Escotilha::find($id);

            if (!$escotilha) {
                return response()->json(['message' => 'Registro da escotilha não encontrado'], 404);
            }

            $escotilha->delete();

            return response()->json(['message' => 'Registro da escotilha excluído com sucesso']);
        } catch (\Exception $e) {
            \Log::error('Erro ao excluir registro da escotilha: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao excluir registro da escotilha'], 500);
        }
    }
}
