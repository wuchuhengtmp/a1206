<?php

return [
    'host' => env('WS_MQTT_Client_HOST'),
    'port' => (int) env('WS_MQTT_Client_PORT'),
    'time_out' => (int) env('WS_MQTT_Client_TIME_OUT'),
    'username' => env('WS_MQTT_Client_USERNAME'),
    'password' => env('WS_MQTT_Client_PASSWORD'),
    'client_id' => env('WS_MQTT_Client_CLIENT_ID'),
    'keepalive' => env('WS_MQTT_Client_KEEPALIVE')
];