<?php

namespace zkelo\Unitpay\Models;

use InvalidArgumentException;
use zkelo\Unitpay\Interfaces\RequestInterface;

/**
 * Request model
 *
 * @version 1.0.0
 */
class Request implements RequestInterface
{
    /**
     * Метод входящего запроса: CHECK
     *
     * Проверка возможности оказания услуг абоненту. Запрос выполняется до выполнения оплаты
     */
    const METHOD_CHECK = 'check';

    /**
     * Метод входящего запроса: PAY
     *
     * Уведомление об успешном платеже
     */
    const METHOD_PAY = 'pay';

    /**
     * Метод входящего запроса: PREAUTH
     *
     * Уведомление о платеже с преавторизацией, когда средства были успешно заблокированы
     */
    const METHOD_PREAUTH = 'preAuth';

    /**
     * Метод входящего запроса: ERROR
     *
     * Ошибка платежа на любой из этапов. Если ошибка вызвана пустым или ошибочным ответом сервера партнёра, то запрос не будет отправлен. Следует учесть, что данный статус не конечный и возможны ситуации, когда после запроса ERROR может последовать запрос PAY
     */
    const METHOD_ERROR = 'error';

    /**
     * Request method name
     *
     * @var string
     */
    protected $method;

    /**
     * Список IP-адресов серверов Unitpay
     *
     * @var string[]
     */
    protected $allowedIps = [
        '31.186.100.49',
        '178.132.203.105',
        '52.29.152.23',
        '52.19.56.234'
    ];

    /**
     * Returns supported request methods
     *
     * @return array
     */
    public static function methods(): array
    {
        return [
            static::METHOD_CHECK,
            static::METHOD_PAY,
            static::METHOD_PREAUTH,
            static::METHOD_ERROR
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function __construct(string $method)
    {
        if (!in_array($method, static::methods())) {
            throw new InvalidArgumentException('This request method is not supported');
        }

        $this->method = $method;
    }

    /**
     * Checks a client IP by whitelist
     *
     * @param string $ip IP
     * @return boolean `true` if IP is allowed or `false` if not
     */
    public function isIpValid(string $ip): bool
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return false;
        }
        return in_array($ip, $this->allowedIps);
    }

    /**
     * {@inheritDoc}
     */
    public function isWaiting(): bool
    {
        return $this->method === static::METHOD_CHECK;
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccess(): bool
    {
        return $this->method === static::METHOD_PAY;
    }

    /**
     * {@inheritDoc}
     */
    public function isPreAuth(): bool
    {
        return $this->method === static::METHOD_PREAUTH;
    }

    /**
     * {@inheritDoc}
     */
    public function hasFailed(): bool
    {
        return $this->method === static::METHOD_ERROR;
    }

    /**
     * Validates request
     *
     * @param string $ip Client IP
     * @param array $data Request data
     * @return boolean `true` if request is valid or `false` if not
     */
    public function validate(string $ip, array $data): bool
    {
        if (!$this->isIpValid($ip)) {
            return false;
        }
        // TODO Написать код проверки запроса
        return true;
    }
}
