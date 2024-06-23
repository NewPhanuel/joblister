<?php
declare(strict_types=1);

namespace Framework\Middleware;

use Framework\Session;

class Authorize
{
    /**
     * Checks if user is authenticated
     *
     * @return boolean
     */
    public function isAuthenticated(): bool
    {
        return Session::has('user');
    }

    /**
     * Handles a request
     *
     * @param string $role
     * @return void
     */
    public function handle(string $role): void
    {
        if ($role === 'guest' && $this->isAuthenticated()) {
            redirect('/');
            return;
        } else if ($role === 'auth' && !$this->isAuthenticated()) {
            redirect('/auth/login');
            return;
        }
    }
}