<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;
use Carbon\Carbon;
use App\Services\ApiResponse;

class SensorDataController extends Controller
{
    public function inserirSensorData(Request $request){

        $request->validate([
            'serial_number' => 'required|string|exists:escotilhas,serial_number',
            'distancia' => 'required|numeric',
            'luz_ambiente' => 'required|numeric',
        ]);

        try {
            $escotilha = Escotilha::where('serial_number', $request->serial_number)->first();

            $sensorData = SensorData::create([
                'escotilha_id' => $escotilha->id,
                'distancia' => $request->distancia,
                'luz_ambiente' => $request->luz_ambiente,
                'hora_atualizacao' => now(),
            ]);

            return ApiResponse::success([
                'message' => 'Dados do sensor inseridos com sucesso',
                'data' => $sensorData,
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Erro ao inserir dados do sensor: ' . $e->getMessage());
            return ApiResponse::error('Erro ao inserir dados do sensor');
        }
    }

    public function listarSensorData()
    {
        if (!auth()->user()->tokenCan('admin, enterprise')) {
            return ApiResponse::unauthorized();
        }

        try {
            $dados = SensorData::with('escotilha')
                ->created_at('hora_atualizacao', 'desc')
                ->get();

            return ApiResponse::success([
                'message' => 'Lista de dados dos sensores',
                'data' => $dados,
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao listar dados dos sensores: ' . $e->getMessage());
            return ApiResponse::error('Erro ao listar dados dos sensores');
        }
    }

    public function deletarSensorData($id)
    {
        try {
            $registro = SensorData::find($id);

            if (!$registro) {
                return ApiResponse::error('Registro não encontrado');
            }

            $registro->delete();

            return ApiResponse::success([
                'message' => 'Registro excluído com sucesso',
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao excluir dado do sensor: ' . $e->getMessage());
            return ApiResponse::error('Erro ao excluir dado do sensor');
        }
    }

    public function obterSensorDataPorEscotilha($escotilhaId)
    {
        try {
            $dados = SensorData::where('escotilha_id', $escotilhaId)
                ->created_at('hora_atualizacao', 'desc')
                ->get();

            return ApiResponse::success([
                'message' => 'Dados da escotilha encontrados',
                'data' => $dados,
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar dados da escotilha: ' . $e->getMessage());
            return ApiResponse::error('Erro ao buscar dados da escotilha');
        }
    }
}
