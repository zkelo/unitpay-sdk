<?php

namespace zkelo\Unitpay\Models;

use InvalidArgumentException;

/**
 * Locale model
 *
 * @author Aleksandr Riabov <ar161ru@gmail.com>
 * @version 1.0.0
 */
class Locale
{
    /**
     * Locale code: English
     */
    const ENGLISH = 'en';

    /**
     * Locale code: Russian
     */
    const RUSSIAN = 'ru';

    /**
     * Locale code
     *
     * @var string
     */
    protected $code;

    /**
     * Returns locales list
     *
     * @return array
     */
    public static function list(): array
    {
        return [
            static::ENGLISH,
            static::RUSSIAN
        ];
    }

    /**
     * Checks if locale supported
     *
     * @param string $code Locale code
     * @return boolean `true` if locale is supported or `false` if not
     */
    public static function isSupported(string $code): bool
    {
        $list = static::list();
        return in_array($code, $list);
    }

    /**
     * Constructs a locale model
     *
     * @param string $code Locale code
     * @return void
     * @throws InvalidArgumentException If specified locale is not supported
     */
    public function __construct(string $code)
    {
        if (!static::isSupported($code)) {
            throw new InvalidArgumentException('This locale is not supported');
        }

        $this->code = $code;
    }

    /**
     * Returns locale code
     *
     * @return string
     */
    public function code(): string
    {
        return $this->code;
    }

    /**
     * Loads locale specified in a model instance
     *
     * @return boolean
     */
    protected function load(): bool
    {
        return false;
    }
}
