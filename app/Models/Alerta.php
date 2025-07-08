<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alerta extends Model
{
    use SoftDeletes;

    protected $table = 'alertas';

    protected $fillable = [
        'sensor_data_id',
        'type',
        'message',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function sensorData()
    {
        return $this->belongsTo(SensorData::class);
    }

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }
    
    public function escotilha()
    {
        return $this->belongsTo(Escotilha::class);
    }
}
