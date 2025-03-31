<?php

namespace User\Service;

require_once dirname(__DIR__) . '/Manager/GroupManager.php';
require_once dirname(__DIR__) . '/Manager/PositionManager.php';
require_once dirname(__DIR__) . '/Manager/UserManager.php';

use User\Manager\GroupManager as Groups;
use User\Manager\PositionManager as Positions;
use User\Manager\UserManager as Users;

class UserService
{
    private Groups $groups;

    private Positions $positions;

    private Users $user;

    function __construct()
    {
        $this->groups = new Groups();
        $this->positions = new Positions();
        $this->user = new Users();
    }

    public function logInUserByPassword(string $email, string $password): bool
    {
        if ($user = $this->user->verify($email, $password)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['view_users_task_id'] = $user->id;
            $_SESSION['name'] = $user->name;
            $_SESSION['view_users_name'] = $user->name;
            $_SESSION['position_id'] = $user->position_id;
            return true;
        }
        return false;
    }

    public function registerUser(string $name, string $email, string $password): bool
    {
        $user = [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ];

        if ($this->user->add($user) > 0) {
            return true;
        }
        return false;
    }

    public function getAllPatrolLeaders(): array
    {
        return $this->user->getAllPatrolLeaders();
    }

    public function getAllLeaders(): array
    {
        return $this->user->getAllLeaders();
    }

    public function getAllUsers(): array
    {
        return $this->user->getAll();
    }

    public function getAllMembers(int $leader_id): array
    {
        $result = array();

        $members = $this->groups->getGroup($leader_id);

        foreach ($members as $member) {
            $user = $this->user->get($member->user_id);

            if (isset($user->id)) {
                $result[] = $user;
            }
        }

        return $result;
    }

    public function getAllUncategorizedUsers(): array
    {
        $result = array();

        $users = $this->user->getAllScouts();

        foreach ($users as $user) {
            if (!$this->groups->isMemberOfGroup($user->id)) {
                $result[] = $user;
            }
        }

        return $result;
    }

    public function getPositions(): array
    {
        return $this->positions->getAll();
    }

    public function setLeader(int $user_id): bool
    {
        $this->groups->removeGroup($user_id);
        $this->groups->removeMemberOfGroup($user_id);
        $response = $this->user->update($user_id, "position_id", "4");

        if ($response) {
            return true;
        }
        return false;
    }

    public function setPatrolLeader(int $user_id): bool
    {
        $this->groups->removeMemberOfGroup($user_id);
        $response1 = $this->groups->addGroupMember($user_id, $user_id);
        $response2 = $this->user->update($user_id, "position_id", "3");

        if ($response1 && $response2) {
            return true;
        }
        return false;
    }

    public function setScout(int $user_id, int $leader_id): bool
    {
        $this->groups->removeGroup($user_id);
        $this->groups->removeMemberOfGroup($user_id);
        $response1 = true;

        if ($leader_id != 0 && $leader_id != $user_id) {
            $response1 = $this->groups->addGroupMember($leader_id, $user_id);
        }

        $this->user->update($user_id, "position_id", "1");

        if ($response1) {
            return true;
        }
        return false;
    }
}

?>