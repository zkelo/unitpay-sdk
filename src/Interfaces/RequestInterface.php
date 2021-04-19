<?php

namespace zkelo\Unitpay\Interfaces;

/**
 * Interface for classes that represents a request from Unitpay
 *
 * @author Aleksandr Riabov <ar161ru@gmail.com>
 * @version 1.0.0
 */
interface RequestInterface
{
    /**
     * Checks if request is valid
     *
     * @param array $data Request data
     * @param boolean $throwException Whether to throw an exception if request is invalid _(otherwise this method just return `false`)_
     * @return boolean `true` if request is valid or `false` if not
     */
    public static function isValid(array $data, bool $throwException = true): bool;

    /**
     * Handles request
     *
     * @param array $data Request data
     * @return string Response that need to be returned for request
     */
    public static function handle(array $data): string;
}
