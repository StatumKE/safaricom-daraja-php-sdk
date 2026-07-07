<?php

declare(strict_types=1);

use Statum\Safaricom\Daraja\Environment\Environment;

return [
    'consumer_key' => '',
    'consumer_secret' => '',
    'environment' => Environment::Sandbox->value,
    'timeout' => 30,
    'connect_timeout' => 10,
    'default_headers' => [],
];
