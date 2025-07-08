<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escotilha extends Model
{
    protected $table = 'escotilhas';

    protected $fillable = [
        'serial_number',
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
}
