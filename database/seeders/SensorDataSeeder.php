<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SensorData;

class SensorDataSeeder extends Seeder
{
    public function run(): void
    {
        $sensorData = new SensorData();
        $sensorData->escotilha_id = 1; // Assuming the escotilha with ID 1 exists
        $sensorData->sensor_id = 1; // Assuming the sensor with ID 1 exists
        $sensorData->valor = 15.5; // Example value
        $sensorData->save();

        $sensorData = new SensorData();
        $sensorData->escotilha_id = 1; // Assuming the escotilha with ID 1 exists
        $sensorData->sensor_id = 2; // Assuming the sensor with ID 2 exists
        $sensorData->valor = 80.0;
        $sensorData->save();

    }
}
