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
        Schema::create('alertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escotilha_id')->constrained()->onDelete('cascade');
            $table->string('tipo'); // ex: 'ABERTURA', 'FECHAMENTO', 'ERRO_SENSOR', 'DESCONEXÃO'
            $table->string('mensagem'); // ex: 'A comporta foi aberta manualmente'
            $table->timestamp('data_hora')->useCurrent();
            $table->string('origem')->nullable(); // ex: 'esp32', 'app'
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertas');
    }
};
