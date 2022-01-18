# Unitpay PHP SDK

![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/zkelo/unitpay-sdk)
![CodeFactor Grade](https://img.shields.io/codefactor/grade/github/zkelo/unitpay-sdk)
![Packagist Downloads](https://img.shields.io/packagist/dt/zkelo/unitpay-sdk)
![Packagist License](https://img.shields.io/packagist/l/zkelo/unitpay-sdk)

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

## Quick start

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
    // Do something on success request
} else {
    // Do something on bad request
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

> In example above payment method written as is, e.g. "raw". But instead of writing payment method by hand you can use constants from models. Please refer to [models](#models) section for more information.

### Retrieving information about payment

To retrieve payment information you should use `getPayment()` method that returns information in comfortable way using model.

```php
// Payment ID in Unitpay (this is not order ID in your app or something else!)
$paymentId = 7777777777;

// Retrieving information
$paymentInfo = $sdk->getPayment($paymentId);

// Display order amount and currency
echo "Order amount: $paymentInfo->orderSum (currency: $paymentInfo->orderCurrency)", PHP_EOL;
```

### Localization

- *ToDo.*

# Reference

*This section will be written soon.*

## Exceptions

- *ToDo.*

## Interfaces

- *ToDo.*

## Available methods

- *ToDo.*

## Locales

- *ToDo.*

## Models

- *ToDo.*

# Extending

## Localization

If you need to translate currency names, payment methods or response messages to your language you can do it simply by making new locale class which extending base abstract `Locale` class.

Example below shows how looks locale class.

```php

namespace App\Locales;

/**
 * My own locale
 */
class MyLocale extends Locale implements LocaleInterface
{
    /**
     * {@inheritDoc}
     */
    public static function messages(): array
    {
        return [
            // Write translations here.
            //
            // For example, if you want to translate some currencies names,
            // you just need to specify all its messages inside `currency` property
            // which will be array which has currencies IDs as keys
            // and translation messages for each of them as values.
            'currency' => [
                // Don't use raw names of currencies,
                // payment methods and etc. in your
                // locale class like here:
                //
                'RUB' => 'Russian rouble', // <-- This is wrong!
                // Instead of using RAWs
                // you should use model
                // constants like here:
                //
                Currency::RUB => 'Russian rouble' // <-- This is right!
            ]
        ];
    }
}
```

Now, when you have class for your locale, you need to make it available for usage by adding it to `Locale` model by `Locale::use()` method.

```php
use zkelo\Unitpay\Models\Locale;

// In this example `en_GB` is name of your locale
Locale::use('en_GB', App\Locales\MyLocale::class);
```

After adding locale you can use it in SDK. You can set it as default or just specify it in places where needed.

```php
// Specifying locale as default for SDK
$sdk->setDefaultLocale('en_GB');

// Or... specifying locale in "real-time"
$sdk->form(10, 6, 'Test payment', zkelo\Unitpay\Models\Payment::METHOD_CARD, zkelo\Unitpay\Models\Currency::RUB, 'en_GB');
```
