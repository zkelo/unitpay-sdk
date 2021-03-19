<?php

namespace zkelo\Unitpay\Interfaces;

/**
 * Interface for request models
 *
 * @version 1.0.0
 */
interface RequestInterface
{
    /**
     * Constructs a request
     *
     * @param string $method Request method name
     * @return void
     */
    public function __construct(string $method);

    /**
     * Checks if request has waiting
     *
     * @return boolean
     */
    public function isWaiting(): bool;

    /**
     * Checks if request has success status
     *
     * @return boolean
     */
    public function isSuccess(): bool;

    /**
     * Checks if request has preauth status
     *
     * @return boolean
     */
    public function isPreAuth(): bool;

    /**
     * Checks if request has been failed at any step
     *
     * @return boolean
     */
    public function hasFailed(): bool;
}
