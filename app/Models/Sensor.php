<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $table = 'sensores';

    protected $fillable = [
        'name',
        'type',
    ];

    public function sensorData()
    {
        return $this->hasMany(SensorData::class);
    }
    public function alerts()
    {
        return $this->hasMany(Alerta::class);
    }
    public function escotilha()
    {
        return $this->belongsTo(Escotilha::class);
    }
}
