<?php

namespace zkelo\Unitpay\Models;

use InvalidArgumentException;
use zkelo\Unitpay\Interfaces\LocaleInterface;
use zkelo\Unitpay\Locales\{
    En,
    Ru
};

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
     * Handlers that will be used to retrieve locale messages
     *
     * @var array
     */
    private static $handlers = [
        static::ENGLISH => En::class,
        static::RUSSIAN => Ru::class
    ];

    /**
     * Locale code
     *
     * @var string
     */
    protected $code;

    /**
     * Localized messages
     *
     * @var string[]
     */
    protected $messages;

    /**
     * Locale handler
     *
     * @var LocaleInterface
     */
    protected $handler;

    /**
     * Returns locales list
     *
     * @return array
     */
    public static function list(): array
    {
        return array_keys(static::$handlers);
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
     * Makes model to use certain handler class for locale
     *
     * @param string $code Locale code
     * @param string $handler Handler class fully-qualified path
     * @return void
     * @throws InvalidArgumentException If handler class not found
     */
    public static function use(string $code, string $handler): void
    {
        if (!class_exists($handler)) {
            throw new InvalidArgumentException("Class \"$handler\" is not defined");
        }

        static::$handlers[$code] = $handler;
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
        $this->handler = new static::$handlers[$this->code];
        $this->messages = $this->handler::rawMessages();
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
     * Returns locale handler
     *
     * @return LocaleInterface
     */
    public function handler(): LocaleInterface
    {
        return $this->handler;
    }

    /**
     * Returns message
     *
     * @param string $key Message key
     * @return string|null Message or `null` if specified key not found
     */
    public function message(string $key): ?string
    {
        if (!isset($this->messages[$key])) {
            return null;
        }
        return $this->messages[$key];
    }

    /**
     * Alias of `message()`
     *
     * @see static::message
     */
    public function t(string $key): ?string
    {
        return $this->message($key);
    }
}
