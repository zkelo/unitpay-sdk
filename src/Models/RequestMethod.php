<?php

namespace zkelo\Unitpay\Models;

/**
 * Request method model
 *
 * @version 1.0.0
 */
class RequestMethod
{
    /**
     * Метод входящего запроса: CHECK
     *
     * Проверка возможности оказания услуг абоненту. Запрос выполняется до выполнения оплаты
     */
    const CHECK = 'check';

    /**
     * Метод входящего запроса: PAY
     *
     * Уведомление об успешном платеже
     */
    const PAY = 'pay';

    /**
     * Метод входящего запроса: PREAUTH
     *
     * Уведомление о платеже с преавторизацией, когда средства были успешно заблокированы
     */
    const PREAUTH = 'preAuth';

    /**
     * Метод входящего запроса: ERROR
     *
     * Ошибка платежа на любой из этапов. Если ошибка вызвана пустым или ошибочным ответом сервера партнёра, то запрос не будет отправлен. Следует учесть, что данный статус не конечный и возможны ситуации, когда после запроса ERROR может последовать запрос PAY
     */
    const ERROR = 'error';

    /**
     * Returns supported request methods
     *
     * @return array
     */
    public static function list(): array
    {
        return [
            static::CHECK,
            static::PAY,
            static::PREAUTH,
            static::ERROR
        ];
    }
}
