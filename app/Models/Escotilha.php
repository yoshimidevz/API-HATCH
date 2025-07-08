<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Escotilha extends Model
{
    use SoftDeletes;

    protected $table = 'escotilhas';

    protected $fillable = [
        'serial_number',
        'user_id',
    ];

    public function sensorData()
    {
        return $this->hasMany(SensorData::class);
    }
    public function sensores()
    {
        return $this->hasMany(Sensor::class);
    }
    public function alertas()
    {
        return $this->hasMany(Alerta::class);
    }

    // Adicionar a relação belongsTo com o modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
