<?php

namespace zkelo\Unitpay\Models;

use InvalidArgumentException;

/**
 * Operator model
 *
 * @author Aleksandr Riabov <ar161ru@gmail.com>
 * @version 1.0.0
 */
class Operator
{
    /**
     * Оператор: МТС
     */
    const MTS = 'mts';

    /**
     * Оператор: Мегафон
     */
    const MEGAFON = 'mf';

    /**
     * Оператор: Билайн
     */
    const BEELINE = 'beeline';

    /**
     * Оператор: Теле2
     */
    const TELE2 = 'tele2';

    /**
     * Operator code
     *
     * @var string
     */
    protected $code;

    /**
     * Returns list of supported operators codes
     *
     * @return array
     */
    public static function list(): array
    {
        return [
            static::MTS => 'МТС',
            static::MEGAFON => 'Мегафон',
            static::BEELINE => 'Билайн',
            static::TELE2 => 'Теле2'
        ];
    }

    /**
     * Checks if specified operator is supported
     *
     * @param string $code
     * @return boolean `true` if operator is supported or `false` if not
     */
    public static function isSupported(string $code): bool
    {
        $list = array_keys(static::list());
        return in_array($code, $list);
    }

    /**
     * Construct an operator model
     *
     * @param string $code Operator code
     */
    public function __construct(string $code)
    {
        if (!static::isSupported($code)) {
            throw new InvalidArgumentException('This operator is not supported');
        }

        $this->code = $code;
    }

    /**
     * Returns operator code
     *
     * @return string
     */
    public function code(): string
    {
        return $this->code;
    }
}
