<?php

namespace zkelo\Unitpay\Models;

/**
 * Locale model
 *
 * @version 1.0.0
 */
class Locale
{
    /**
     * Язык: Английский
     */
    const ENGLISH = 'en';

    /**
     * Язык: Русский
     */
    const RUSSIAN = 'ru';

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
}
