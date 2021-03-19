<?php

use zkelo\Unitpay\SDK;

require_once __DIR__ . '/../vendor/autoload.php';
$config = require_once __DIR__ . '/config.php';

// Создание экземпляра SDK
$unitpay = new SDK($config['secretKey'], $config['publicKey'], $config['domain']);

// Включение тестового режима
$unitpay->toggleTestMode(true);
