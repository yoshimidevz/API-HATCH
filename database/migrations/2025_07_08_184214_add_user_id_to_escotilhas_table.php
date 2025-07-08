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
        Schema::table('escotilhas', function (Blueprint $table) {
            $table->foreignId('user_id')
                  ->nullable() // Ou ->constrained() se cada escotilha DEVE ter um usuário
                  ->constrained() // Cria a foreign key para a tabela 'users'
                  ->onDelete('set null'); // Ou 'cascade' se quiser deletar escotilhas ao deletar usuário
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('escotilhas', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
