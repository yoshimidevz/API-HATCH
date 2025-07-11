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

            $table->foreignId('sensor_id')
                  ->constrained('sensores')
                  ->onDelete('cascade');

            $table->float('valor');

            $table->timestamp('timestamp')->useCurrent();

            $table->timestamps();
            $table->softDeletes(); // Opcional
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};
