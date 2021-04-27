# Unitpay PHP SDK

> Language: [Русский](README_ru.md) | English

This SDK allows you to work with Unitpay payment system on PHP.

# Manual

## Requirements

- PHP 7.1 or newer;
- Composer.

## Installation

```
composer require zkelo/unitpay-sdk
```

## Initial configuration

SDK constructor requires at least two arguments to be passed in - it's your secret and public key. Optional third argument is domain name that will be used to interact with.

```php
use zkelo\Unitpay\Unitpay;

$secretKey = 'Your secret key';
$publicKey = 'Your public key';

$sdk = new Unitpay($secretKey, $publicKey);

```

## Usage

### Creating payment form link

Following example creates payment form link and redirects user to it immediately.

```php
// Order amount
$amount = 5;

// Order ID
$orderId = 161;

// Order description
$description = "Order $orderId (test)";

// Creating form link
$url = $sdk->form($amount, $orderId, $description);

// Redirecting user to form
header("Location: $url");
```

### Hanling incoming request

Next example handles incoming Unitpay request using SDK and returns correspond response (even if request is bad).

```php
// Retrieving IP
$remoteIp = $_SERVER['REMOTE_ADDR'];

// Retrieving request data from `$_GET` array using `filter_input()` function
$requestData = filter_input_array(INPUT_GET);

// Handling request using SDK
$response = $sdk->handleRequest($remoteIp, $requestData, $success);

// Returning response
echo json_encode($response);
```

> **Be aware!** SDK method returns array you must encode to JSON so you need to use `json_encode()` function.

If you need to do something on success or fail request you can use third argument passed to method which reference to variable that will be used to store boolean value about request status.

As you can see in example above we passed `$success` variable as third argument to method so we can easy check request status.

```php
// ...

// Handling request using SDK
$response = $sdk->handleRequest($remoteIp, $requestData, $success);

if ($success) {
    echo 'Request is success', PHP_EOL;
} else {
    echo 'Bad request', PHP_EOL;
}

// Returning response
echo json_encode($response);
```

### Creating payment by API request

Sometimes you need to init payment through request to API instead of using Unitpay form.

```php
// Order amount
$amount = 5;

// Order ID
$orderId = 161;

// Order description
$description = "Order $orderId (test)";

// User IP (can be either IPv4 or IPv6)
$ip = '127.0.0.1';

// Creating payment
$paymentId = $sdk->initPayment('card', $orderId, $amount, $description, $ip);
```

### Retrieving information about payment

To retrieve payment information you should use `getPayment()` method that returns information in comfortable way using model.

```php
// Payment ID in Unitpay (not order ID in your app or something else!)
$paymentId = 7777777777;

// Retrieving information
$paymentInfo = $sdk->getPayment();

// Display order amount and currency
echo "Order amount: $paymentInfo->orderSum (currency: $paymentInfo->orderCurrency)", PHP_EOL;
```

# Reference

*ToDo...*
