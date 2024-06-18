<?php
declare(strict_types=1);

namespace Framework;

class Session
{
    /**
     * Starts the session
     *
     * @return void
     */
    public static function start(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a session key/value pair
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session value by the key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    /**
     * Checks if session key exists
     *
     * @param string $key
     * @return boolean
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Clear session by key
     *
     * @param string $key
     * @return void
     */
    public static function clear(string $key): void
    {
        if ($_SESSION[$key]) {
            unset($_SESSION[$key]);
        }
    }

    public static function clearAll(): void
    {
        session_unset();
        session_destroy();
    }
}