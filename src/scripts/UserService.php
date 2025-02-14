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

    public function getAllLeaders(): array
    {
        return $this->user->getAllLeaderUsers();
    }

    public function getAllUncategorizedUsers(): array
    {
        return $this->user->getAllUncategorizedUsers();
    }

    public function getAllGroups(): array
    {
        return getStructuredArray('leader_name', $this->groups->getAllGroups());
    }

    public function getUsersPosition($user_id): int
    {
        return $this->user->getUserPosition($user_id);
    }

    public function getPositions(): array
    {
        return $this->positions->getAllPositions();
    }

    public function getAllGroupLeaders(): array
    {
        return $this->groups->getAllLeaders();
    }

    public function setLeader($user_id): bool
    {
        $response1 = $this->groups->deleteGroup($user_id);
        $response2 = $this->groups->deleteMemberOfGroup($user_id);
        $response3 = $this->user->setUsersPosition($user_id, 4);

        $this->groups->fetchGroups();
        $this->groups->fetchLeaders();

        if ($response1 && $response2 && $response3) {
            return true;
        }
        return false;
    }

    public function setAdvisor($user_id): bool
    {
        $response1 = $this->groups->deleteMemberOfGroup($user_id);
        $response2 = $this->groups->addGroupMember($user_id, $user_id);
        $response3 = $this->user->setUsersPosition($user_id, 3);

        $this->groups->fetchGroups();
        $this->groups->fetchLeaders();

        if ($response1 && $response2 && $response3) {
            return true;
        }
        return false;
    }

    public function setScout($user_id, $leader_id): bool
    {
        $response1 = $this->groups->deleteGroup($user_id);
        $response2 = $this->groups->deleteMemberOfGroup($user_id);
        $response3 = true;
        if ($leader_id != 0 && $leader_id != $user_id) {
            $response3 = $this->groups->addGroupMember($user_id, $leader_id);
        }
        $response4 = $this->user->setUsersPosition($user_id, 1);

        $this->groups->fetchGroups();
        $this->groups->fetchLeaders();

        if ($response1 && $response2 && $response3 && $response4) {
            return true;
        }
        return false;
    }
}

?>