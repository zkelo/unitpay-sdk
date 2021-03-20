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
     * Checks if message with specified key exists
     *
     * @param string $key Message key
     * @return boolean `true` if message found or `false` if not
     */
    public static function hasMessage(string $key): bool;

    /**
     * Returns localized message
     *
     * @param string $key Message key
     * @return string|null Message or `null` if key not found
     */
    public static function message(string $key): ?string;

    /**
     * Alias of `message()`
     *
     * @see static::message
     */
    public static function t(string $key): ?string;
}
