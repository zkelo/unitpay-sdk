<?php

namespace zkelo\Unitpay\Interfaces;

/**
 * Interface for locale classes
 *
 * @author Aleksandr Riabov <ar161ru@gmail.com>
 * @version 1.0.0
 */
interface LocaleInterface
{
    /**
     * Returns all messages that supported by locale
     *
     * @return array
     */
    public static function messages(): array;

    /**
     * Returns two-dimensional array with messages with dot-syntax keys
     *
     * @return array
     */
    public static function rawMessages(): array;
}
