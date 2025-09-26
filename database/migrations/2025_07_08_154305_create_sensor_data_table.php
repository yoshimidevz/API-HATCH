<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escotilha_id')
                ->constrained('escotilhas')
                ->onDelete('cascade');

            $table->float('distancia')->nullable();
            $table->float('luz_ambiente')->nullable();

            $table->timestamp('hora_atualizacao')->useCurrent();

            $table->timestamps();
            $table->softDeletes();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};
