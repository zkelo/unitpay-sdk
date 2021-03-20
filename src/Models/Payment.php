<?php

namespace zkelo\Unitpay\Models;

use InvalidArgumentException;

/**
 * Payment model
 *
 * @author Aleksandr Riabov <ar161ru@gmail.com>
 * @version 1.0.0
 */
class Payment
{
    /**
     * Способ оплаты: Мобильные телефоны
     */
    const METHOD_MOBILE = 'mc';

    /**
     * Способ оплаты: Банковские карты
     */
    const METHOD_CARD = 'card';

    /**
     * Способ оплаты: Кошелёк Webmoney Z _(долларовый кошелёк)_
     */
    const METHOD_WEBMONEY_Z = 'webmoney';

    /**
     * Способ оплаты: Кошелёк Webmoney R _(рублёвый кошелёк)_
     */
    const METHOD_WEBMONEY_R = 'webmoneyWmr';

    /**
     * Способ оплаты: ЮMoney _(бывшие "Яндекс.Деньги")_
     */
    const METHOD_YOOMONEY = 'yandex';

    /**
     * Способ оплаты: QIWI
     */
    const METHOD_QIWI = 'qiwi';

    /**
     * Способ оплаты: Paypal
     */
    const METHOD_PAYPAL = 'paypal';

    /**
     * Способ оплаты: Apple Pay
     */
    const METHOD_APPLE_PAY = 'applepay';

    /**
     * Способ оплаты: Samsung Pay
     */
    const METHOD_SAMSUNG_PAY = 'samsungpay';

    /**
     * Способ оплаты: Google Pay
     */
    const METHOD_GOOGLE_PAY = 'googlepay';

    /**
     * Payment method name
     *
     * @var string
     */
    protected $method;

    /**
     * Returns supported payment methods list
     *
     * @return array
     */
    public static function methods(): array
    {
        return [
            static::METHOD_MOBILE,
            static::METHOD_CARD,
            static::METHOD_WEBMONEY_Z,
            static::METHOD_WEBMONEY_R,
            static::METHOD_YOOMONEY,
            static::METHOD_QIWI,
            static::METHOD_PAYPAL,
            static::METHOD_APPLE_PAY,
            static::METHOD_SAMSUNG_PAY,
            static::METHOD_GOOGLE_PAY
        ];
    }

    /**
     * Checks if payment method is supported
     *
     * @param string $method Method name
     * @return boolean `true` if method is supported or `false` if not
     */
    public static function isMethodSupported(string $method): bool
    {
        $methods = static::methods();
        return in_array($method, $methods);
    }

    /**
     * Constructs a payment model
     *
     * @param string $method Payment method name
     * @return void
     * @throws InvalidArgumentException If specified payment method is not supported
     */
    public function __construct(string $method)
    {
        if (!static::isMethodSupported($method)) {
            throw new InvalidArgumentException('This payment method is not supported');
        }

        $this->method = $method;
    }

    /**
     * Returns payment method
     *
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }
}
