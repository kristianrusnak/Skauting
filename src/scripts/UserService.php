<?php

class UserService
{
    private PositionManager $positions;

    private GroupManager $groups;

    private UserManager $user;

    function __construct($database)
    {
        $this->positions = new PositionManager($database);
        $this->groups = new GroupManager($database);
        $this->user = new UserManager($database);
    }

    public function logInUserByPassword($email, $password): bool
    {
        if ($user = $this->user->verifyUser($email, $password)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['view_users_task_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['view_users_name'] = $user['name'];
            $_SESSION['position_id'] = $user['position_id'];
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

    public function getGroup($leader_id): array
    {
        return $this->groups->getGroup($leader_id);
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