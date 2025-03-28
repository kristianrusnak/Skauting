<?php

namespace Utility;

class SessionManager
{
    /**
     * @return bool
     */
    public static function areAllValuesSet(): bool
    {
        $NECESSARY_VALUES = ['user_id', 'view_users_task_id', 'position_id', 'name', 'view_users_name'];

        foreach ($NECESSARY_VALUES as $Key) {
            if (!isset($_SESSION[$Key])) {
                return false;
            }
        }
        return true;
    }

    public static function KickIfSessionNotSet(): void
    {
        if (!self::areAllValuesSet()){
            header('Location: login.php');
            exit;
        }
    }
}

?>