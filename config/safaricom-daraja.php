<?php

declare(strict_types=1);

use Statum\Safaricom\Daraja\Environment\Environment;

return [
    'consumer_key' => env('SAFARICOM_CONSUMER_KEY', ''),
    'consumer_secret' => env('SAFARICOM_CONSUMER_SECRET', ''),
    'environment' => env('SAFARICOM_ENVIRONMENT', Environment::Sandbox->value),
    'timeout' => (int) env('SAFARICOM_TIMEOUT', 30),
    'connect_timeout' => (int) env('SAFARICOM_CONNECT_TIMEOUT', 10),
    'default_headers' => [],
];
