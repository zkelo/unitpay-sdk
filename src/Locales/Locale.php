<?php

namespace zkelo\Unitpay\Locales;

use zkelo\Unitpay\Interfaces\LocaleInterface;

/**
 * Base abstract Locale class
 *
 * This class provides base methods and properties for future use in Locale classes
 *
 * @author Aleksandr Riabov <ar161ru@gmail.com>
 * @version 1.0.0
 */
abstract class Locale implements LocaleInterface
{
    /**
     * {@inheritDoc}
     */
    public static function t(string $key): ?string
    {
        return static::message($key);
    }
}
