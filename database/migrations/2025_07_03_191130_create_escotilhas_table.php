<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('escotilhas', function (Blueprint $table) {
            $table->id();
            $table->float('distancia')->default(0);
            $table->float('luz_ambiente')->default(0);
            $table->timestamp('hora_atualizacao')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escotilhas');
    }
};
