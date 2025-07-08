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
        $escotilha->serial_number = 'ESCOTILHA-001';
        $escotilha->save();

        $escotilha = new Escotilha();
        $escotilha->serial_number = 'ESCOTILHA-002';
        $escotilha->save();

        $escotilha = new Escotilha();
        $escotilha->serial_number = 'ESCOTILHA-003';
        $escotilha->save();

        $escotilha = new Escotilha();
        $escotilha->serial_number = 'ESCOTILHA-004';
        $escotilha->save();

        $escotilha = new Escotilha();
        $escotilha->serial_number = 'ESCOTILHA-005';
        $escotilha->save();
    }
}
