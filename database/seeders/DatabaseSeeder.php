<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Escotilha;
use App\Models\Alerta;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Escotilha::create([
            'distancia' => 9,
            'luz_ambiente' => 7,
            'hora_atualizacao' => now(),
        ]);

        Alerta::create([
            'escotilha_id' => 1,
            'tipo' => 'ABERTURA',
            'mensagem' => 'A comporta foi aberta manualmente',
            'data_hora' => now(),
            'origem' => 'esp32'
        ]);
    }
}
