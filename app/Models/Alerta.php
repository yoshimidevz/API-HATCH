<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alerta extends Model
{
    use SoftDeletes;

    protected $table = 'alertas';

    protected $fillable = [
        'escotilha_id',
        'tipo',
        'mensagem',
        'data_hora',
        'origem',
    ];

    protected $dates = [
        'hora_atualizacao',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function escotilha()
    {
        return $this->belongsTo(Escotilha::class);
    }
}
