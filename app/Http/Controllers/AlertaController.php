<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use App\Models\Escotilha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertaController extends Controller
{
    public function listarAlertas(){
        $alertas = Alerta::with('escotilha')->latest()->get();

        return response()->json(['data' => $alertas]);
    }


    public function obterAlertaPorId($id)
    {
        $alerta = Alerta::with('escotilha')->findOrFail($id);
        $user = Auth::user();

        if (!$user->hasRole('admin') && $alerta->escotilha->user_id !== $user->id) {
            return response()->json(['erro' => 'Acesso não autorizado'], 403);
        }

        return response()->json($alerta);
    }
    public function deletarAlerta($id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return response()->json(['erro' => 'Apenas administradores podem excluir alertas'], 403);
        }

        $alerta = Alerta::findOrFail($id);
        $alerta->delete();

        return response()->json(['message' => 'Alerta excluído com sucesso']);
    }
    public function inserirAlerta(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return response()->json(['erro' => 'Apenas administradores podem criar alertas'], 403);
        }

        $validated = $request->validate([
            'escotilha_id' => 'required|exists:escotilhas,id',
            'tipo' => 'required|string|max:50',
            'mensagem' => 'required|string|max:255',
            'data_hora' => 'nullable|date',
            'origem' => 'nullable|string|max:50',
        ]);

        $alerta = Alerta::create([
            'escotilha_id' => $validated['escotilha_id'],
            'tipo' => $validated['tipo'],
            'mensagem' => $validated['mensagem'],
            'data_hora' => $validated['data_hora'] ?? now(),
            'origem' => $validated['origem'] ?? 'manual',
        ]);

        return response()->json($alerta, 201);
    }
    public function editarAlerta(Request $request, $id){
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return response()->json(['erro' => 'Apenas administradores podem editar alertas'], 403);
        }

        $validated = $request->validate([
            'escotilha_id' => 'sometimes|exists:escotilhas,id',
            'tipo' => 'sometimes|string|max:50',
            'mensagem' => 'sometimes|string|max:255',
            'data_hora' => 'nullable|date',
            'origem' => 'nullable|string|max:50',
        ]);

        $alerta = Alerta::find($id);

        if (!$alerta) {
            return response()->json(['message' => 'Alerta não encontrado'], 404);
        }

        $alerta->update($validated);

        return response()->json([
            'message' => 'Alerta atualizado com sucesso',
            'data' => $alerta,
        ]);
    }
}
