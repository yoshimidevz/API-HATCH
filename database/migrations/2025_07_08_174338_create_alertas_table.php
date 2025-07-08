<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alertas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('escotilha_id')
                  ->constrained('escotilhas')
                  ->onDelete('cascade');

            $table->foreignId('sensor_data_id')
                  ->constrained('sensor_data')
                  ->onDelete('cascade');

            $table->string('type');
            $table->string('message');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alertas');
    }
};
