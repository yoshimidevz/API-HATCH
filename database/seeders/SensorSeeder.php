<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sensor;

class SensorSeeder extends Seeder
{
    public function run(): void
    {
        $sensor = new Sensor();
        $sensor->name = 'Sensor Ultrassonico';
        $sensor->type = 'distancia';
        $sensor->save();

        $sensor = new Sensor();
        $sensor->name = 'Sensor Fotoresistor';
        $sensor->type = 'luminosidade';
        $sensor->save();
    }
}
