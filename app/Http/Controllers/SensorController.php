<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;
use App\Services\ApiResponse;

class SensorController extends Controller
{
    public function listarSensores()
    {
        $sensores = Sensor::all();
        return ApiResponse::success($sensores);
    }

    public function obterPorId($id)
    {
        $sensor = Sensor::find($id);

        if (!$sensor) {
            return ApiResponse::error('Sensor não encontrado', 404);
        }

        return ApiResponse::success($sensor);
    }

    public function cadastrar(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        $sensor = Sensor::create([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return ApiResponse::success($sensor, 201);
    }

    public function atualizar(Request $request, $id)
    {
        $sensor = Sensor::find($id);

        if (!$sensor) {
            return ApiResponse::error('Sensor não encontrado', 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        $sensor->update([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return ApiResponse::success($sensor);
    }

    public function deletar($id)
    {
        $sensor = Sensor::find($id);

        if (!$sensor) {
            return ApiResponse::error('Sensor não encontrado', 404);
        }

        $sensor->delete();

        return ApiResponse::success(['message' => 'Sensor deletado com sucesso']);
    }
}
