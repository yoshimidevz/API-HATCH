<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Escotilha;

class EscotilhaSeeder extends Seeder
{
    public function run(): void
    {
        $escotilha = new Escotilha();
        $escotilha->serial_number = 'ESCOTILHA-001'; // Unique serial number
        $escotilha->save();
    }
}
