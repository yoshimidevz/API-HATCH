<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SensorData;
use App\Models\Escotilha;
use App\Models\Sensor;

class SensorDataSeeder extends Seeder
{
    public function run(): void
    {
        // Pega IDs aleatórios existentes para evitar erro de FK
        $escotilhaId1 = Escotilha::inRandomOrder()->value('id');
        $sensorId1 = Sensor::inRandomOrder()->value('id');

        $escotilhaId2 = Escotilha::inRandomOrder()->value('id');
        $sensorId2 = Sensor::inRandomOrder()->value('id');

        if (!$escotilhaId1 || !$sensorId1 || !$escotilhaId2 || !$sensorId2) {
            $this->command->info('Faltam escotilhas ou sensores para popular sensor_data.');
            return;
        }

        SensorData::create([
            'escotilha_id' => $escotilhaId1,
            'sensor_id' => $sensorId1,
            'valor' => 15.5,
        ]);

        SensorData::create([
            'escotilha_id' => $escotilhaId2,
            'sensor_id' => $sensorId2,
            'valor' => 80.0,
        ]);
    }
}
