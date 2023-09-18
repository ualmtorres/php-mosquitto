<?php

require '/var/www/html/vendor/autoload.php';

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Exceptions\MqttClientException;
use Psr\Log\LogLevel;

// $server   = '192.168.1.144';
// $port     = 1884;
$server   = 'mosquitto-3';
$port     = 1883;
$clientId = 's2';
$username = null;
$password = null;
$use_clean_session = false;
$mqtt_version = MqttClient::MQTT_3_1_1;
$topic = $argv[1]
    or exit("Error: Falta el topic\n");

$connectionSettings = (new ConnectionSettings)
    ->setUsername($username)
    ->setPassword($password)
    ->setKeepAliveInterval(60);

try {
    // Create a new instance of an MQTT client and configure it to use the shared broker host and port.
    $client = new MqttClient($server, $port, $clientId, $mqtt_version);

    // Connect to the broker without specific connection settings but with a clean session.
    $client->connect($connectionSettings, $use_clean_session);

    // Subscribe to the topic passed in argv parameter using QoS 2.
    $client->subscribe($topic, function ($topic, $message) {
        printf("Enviando email a partir del payload \n%s\n", $message);
    }, 2);

    // Since subscribing requires to wait for messages, we need to start the client loop which takes care of receiving,
    // parsing and delivering messages to the registered callbacks. The loop will run indefinitely, until a message
    // is received, which will interrupt the loop.
    $client->loop(true);

    // Gracefully terminate the connection to the broker.
    $client->disconnect();
} catch (MqttClientException $e) {
    // MqttClientException is the base exception of all exceptions in the library. Catching it will catch all MQTT related exceptions.
    //$logger->error('Publishing a message using QoS 0 failed. An exception occurred.', ['exception' => $e]);
}
