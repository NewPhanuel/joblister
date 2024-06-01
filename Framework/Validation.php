<?php
declare(strict_types=1);

namespace Framework;

class Validation
{

    /**
     * Make sure the input is a string and it's not greater than the max value
     * or lesser than the $min value
     *
     * @param string $value
     * @param integer $min
     * @param integer $max
     * @return boolean
     */
    public static function string(string $value, int $min = 1, float $max = INF): bool
    {
        if (is_string($value)) {
            $value = trim($value);
            $length = strlen($value);

            return ($length >= $min && $length <= $max);
        }
        return false;
    }

    /**
     * Validate email address
     *
     * @param string $value
     * @return mixed
     */
    public static function email(string $value): mixed
    {
        $value = trim($value);
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Matches a string against another
     *
     * @param string $value1
     * @param string $value2
     * @return boolean
     */
    public static function match(string $value1, string $value2): bool
    {
        $value1 = trim($value1);
        $value2 = trim($value2);

        return ($value1 === $value2);
    }
}