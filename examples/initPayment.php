<?php

use zkelo\Unitpay\SDK;

require_once __DIR__ . '/sdk.php';

$method = SDK::PAYMENT_METHOD_CARD;
$account = 'test';
$sum = 15;
$description = 'Пример использования метода SDK initPayment()';
$ip = '127.0.0.1';

echo 'Номер платежа: ', $unitpay->initPayment($method, $account, $sum, $description, $ip) ?? '(что-то пошло не так)', PHP_EOL;
