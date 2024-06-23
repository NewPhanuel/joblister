<?php
declare(strict_types=1);

namespace Framework;

use Framework\Session;

class Authorize
{
    /**
     * Checks if the current user is the owner of the resource
     *
     * @param integer $resourseId
     * @return boolean
     */
    public static function isOwner(int $resourseId): bool
    {
        $sessionUser = Session::get('user');

        if ($sessionUser !== null && isset($sessionUser['id'])) {
            $sessionUserId = (int) $sessionUser['id'];
            return $sessionUserId === $resourseId;
        }
        return false;
    }
}