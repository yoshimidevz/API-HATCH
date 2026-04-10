<?php
namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class MqttService
{
    public static function publish(string $topic, string $message): void
    {
        $host     = config('mqtt.host');
        $port     = config('mqtt.port');
        $clientId = config('mqtt.client_id');

        $mqtt = new MqttClient($host, $port, $clientId, MqttClient::MQTT_3_1_1);

        $settings = (new ConnectionSettings)
            ->setUseTls(true)
            ->setUsername(config('mqtt.username'))
            ->setPassword(config('mqtt.password'));

        $mqtt->connect($settings, true);
        $mqtt->publish($topic, $message, qualityOfService: 1);
        $mqtt->disconnect();
    }
}
