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

    /**
     * Clears all the session
     *
     * @return void
     */
    public static function clearAll(): void
    {
        session_unset();
        session_destroy();
    }

    /**
     * Sets a flash message
     *
     * @param string $key
     * @param string $message
     * @return void
     */
    public static function setFlash(string $key, string $message): void
    {
        self::set('flash_' . $key, $message);
    }

    /**
     * Returns the value of a flash message
     *
     * @param string $key
     * @param mixed $default
     * @return string|null
     */
    public static function getFlash(string $key, mixed $default = null): ?string
    {
        if (self::has('flash_' . $key)) {
            $message = self::get('flash_' . $key, $default);
            self::clear('flash_' . $key);
            return $message;
        }
        return null;
    }
}