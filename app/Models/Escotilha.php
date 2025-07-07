<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Escotilha extends Model
{
    use SoftDeletes;

    protected $table = 'escotilhas';

    protected $fillable = [
        'distancia',
        'luz_ambiente',
        'hora_atualizacao',
    ];

    protected $dates = [
        'hora_atualizacao',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function alertas()
    {
        return $this->hasMany(Alerta::class);
    }
    public function user(){
        return $this->hasMany(User::class);
    }
}
