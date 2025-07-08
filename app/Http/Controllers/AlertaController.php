<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertaController extends Controller
{
    public function listarAlertas()
    {
        $user = Auth::user();

        $alertas = Alerta::with(['sensorData.escotilha', 'sensorData.sensor']);

        if ($user->role !== 'admin') {
            $alertas->whereHas('sensorData.escotilha', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        return response()->json(['data' => $alertas->latest()->get()]);
    }

    // Obter alerta por ID
    public function obterAlertaPorId($id)
    {
        $user = Auth::user();

        $alerta = Alerta::with(['sensorData.escotilha', 'sensorData.sensor'])->findOrFail($id);

        if ($user->role !== 'admin' && $alerta->sensorData->escotilha->user_id !== $user->id) {
            return response()->json(['erro' => 'Acesso não autorizado'], 403);
        }

        return response()->json($alerta);
    }

    // Deletar alerta (apenas admin)
    public function deletarAlerta($id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['erro' => 'Apenas administradores podem excluir alertas'], 403);
        }

        $alerta = Alerta::findOrFail($id);
        $alerta->delete();

        return response()->json(['message' => 'Alerta excluído com sucesso']);
    }
}
