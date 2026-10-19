<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('escotilhas', function (Blueprint $table) {
            $table->boolean('travado')->default(false)->after('user_id');
            $table->timestamp('liberado_ate')->nullable()->after('travado');
            $table->timestamp('ultima_leitura_em')->nullable()->after('liberado_ate');
        });
    }

    public function down(): void
    {
        Schema::table('escotilhas', function (Blueprint $table) {
            $table->dropColumn(['travado', 'liberado_ate', 'ultima_leitura_em']);
        });
    }
};
