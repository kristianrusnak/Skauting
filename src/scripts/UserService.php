<?php

class UserService
{
    private CookieManager $cookies;

    private PositionManager $positions;

    private GroupManager $groups;

    private UserManager $user;

    function __construct($database, $cookies)
    {
        $this->cookies = $cookies;
        $this->positions = new PositionManager($database);
        $this->groups = new GroupManager($database);
        $this->user = new UserManager($database);
    }

    public function getUserVerify($email, $password): array
    {
        return $this->user->verifyUser($email, $password);
    }

    public function logInUserByPassword($email, $password): bool
    {
        if ($user = $this->user->verifyUser($email, $password)) {
            $this->cookies->deleteAllCookies();
            $this->cookies->setCookie('user_id', $user['id']);
            $this->cookies->setCookie('view_users_task_id', $user['id']);
            $this->cookies->setCookie('name', $user['name']);
            $this->cookies->setCookie('position_id', $user['position_id']);
            return true;
        }
        return false;
    }

    public function registerUser($name, $email, $password): bool
    {
        if ($this->user->addUser($name, $email, $password)) {
            return true;
        }
        return false;
    }
}

?>