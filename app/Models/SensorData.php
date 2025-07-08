<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SensorData extends Model
{
    use SoftDeletes;

    protected $table = 'sensor_data';

    protected $fillable = [
        'escotilha_id',
        'sensor_id',
        'valor',
        'hora_atualizacao',
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
    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }
    public function alertas()
    {
        return $this->hasMany(Alerta::class);
    }
}
