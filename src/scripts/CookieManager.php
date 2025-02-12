<?php

class CookieManager
{
    /**
     * List of necessary cookies
     */
    private const NECESSARY_COOKIES = ['user_id', 'view_users_task_id', 'position_id', 'name'];

    /**
     * @return bool
     */
    public function areAllCookiesSet(): bool
    {
        foreach (self::NECESSARY_COOKIES as $cookieKey) {
            if (!isset($_COOKIE[$cookieKey])) {
                return false;
            }
        }
        return true;
    }

    public function KickIfCookiesNotSet(): void
    {
        if (!$this->areAllCookiesSet()){
            header('Location: ../pages/login.php');
            exit;
        }
    }

    public function anyCookieActive(): bool
    {
        foreach (self::NECESSARY_COOKIES as $cookieKey) {
            if (isset($_COOKIE[$cookieKey])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Sets the cookie
     *
     * @param $key
     * @param $value
     * @return void
     */
    public function setCookie($key, $value): void
    {
        setcookie($key, $value, time() + (86400 * 30), "/");
    }

    /**
     * Deletes all cookies
     *
     * @return void
     */
    public function deleteAllCookies(): void
    {
        foreach (self::NECESSARY_COOKIES as $cookieKey) {
            $this->deleteCookie($cookieKey);
        }
    }

    /**
     * Deletes cookie based on $key
     *
     * @param $key
     * @return void
     */
    public function deleteCookie($key): void
    {
        setcookie($key, "", time() - 3600, "/");
        unset($_COOKIE[$key]);
    }

    /**
     * Checks if I see my own tasks
     *
     * @return bool
     */
    public function doISeeDifferentTaskThanMine(): bool
    {
        return !($_COOKIE['view_users_task_id'] == $_COOKIE['user_id']);
    }

    /**
     * Sets the cookie so user can see his own tasks
     *
     * @return void
     */
    public function setToMyOwnTasks(): void
    {
        setcookie('view_users_task_id', $_COOKIE['user_id'], time() + (86400 * 30), "/");
    }

    /**
     * Changes the cookie so user can view someone else tasks
     *
     * @param $user_id
     * @return void
     */
    public function setToDifferentTasks($user_id): void
    {
        setcookie('view_users_task_id', $user_id, time() + (86400 * 30), "/");
    }
}

?>