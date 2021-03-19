<?php

namespace zkelo\Unitpay\Interfaces;

/**
 * Interface for models
 *
 * @version 1.0.0
 */
interface ModelInterface
{
    public static function list(): array;

    public static function isSupported(string $code): bool
}
