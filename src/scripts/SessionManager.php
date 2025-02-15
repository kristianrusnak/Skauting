<?php

class SessionManager
{
    /**
     * List of necessary values
     */
    private const NECESSARY_VALUES = ['user_id', 'view_users_task_id', 'position_id', 'name', 'view_users_name'];

    /**
     * @return bool
     */
    public function areAllValuesSet(): bool
    {
        foreach (self::NECESSARY_VALUES as $Key) {
            if (!isset($_SESSION[$Key])) {
                return false;
            }
        }
        return true;
    }

    public function KickIfSessionNotSet(): void
    {
        if (!$this->areAllValuesSet()){
            header('Location: ../pages/login.php');
            exit;
        }
    }
}

?>