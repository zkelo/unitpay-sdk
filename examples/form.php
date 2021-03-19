<?php

require_once __DIR__ . '/sdk.php';

$sum = 1; // Сумма
$account = 'Тест'; // Идентификатор абонента
$description = 'Тестовый заказ'; // Описание

$url = $unitpay->form($sum, $account, $description);

echo 'Ссылка на форму: ', $url, PHP_EOL;
