<?php
return [
    'host'     => env('MQTT_HOST'),
    'port'     => env('MQTT_PORT', 8883),
    'username' => env('MQTT_USERNAME'),
    'password' => env('MQTT_PASSWORD'),
    'client_id' => 'laravel-hatch-' . uniqid(),
];
