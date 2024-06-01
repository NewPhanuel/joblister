<?php
declare(strict_types=1);

namespace App\Controllers;

class ErrorController
{
    /**
     * Loads a 404 not found error page
     *
     * @param string $message
     * @return void
     */
    public static function notFound(string $message = 'Resource not found'): void
    {
        http_response_code(404);
        loadView('error', [
            "status" => "404",
            "message" => $message,
        ]);
    }

    /**
     * Loads a 403 not authorised page
     *
     * @param string $message
     * @return void
     */
    public static function unauthorised(string $message = "You are not authorised to view this resource"): void
    {
        http_response_code(403);
        loadView("error", [
            "status" => "403",
            "message" => $message,
        ]);
    }
}