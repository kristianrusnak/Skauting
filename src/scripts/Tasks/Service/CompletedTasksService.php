<?php

namespace Task\Service;

require_once dirname(__DIR__) . '/Manager/CompletedTasksManager.php';

use Task\Manager\CompletedTasksManager as CompletedTasksManager;

class CompletedTasksService
{
    /**
     * @var CompletedTasksManager
     */
    private CompletedTasksManager $completedTasks;

    function __construct($database)
    {
        $this->completedTasks = new CompletedTasksManager($database);
    }

    public function getMeritBadgesInProgress($user_id): array
    {
        return $this->completedTasks->getAllTasksInProgressFromMeritBadge($user_id);
    }

    public function getUnverifiedMeritBadges($user_id, $position_id): array
    {
        if ($position_id >= 4) {
            return $this->completedTasks->getAllUsersUnverifiedMeritBadge($user_id, true);
        } else {
            return $this->completedTasks->getAllUsersUnverifiedMeritBadge($user_id);
        }
    }

    public function getScoutPathsInProgress($user_id): array
    {
        $scoutPaths = $this->completedTasks->getAllTasksInProgressFromScoutPath($user_id);
        $result = array();

        $last_scout_path_id = '';
        $array = array();
        foreach ($scoutPaths as $scoutPath) {
            if ($last_scout_path_id == '') {
                $last_scout_path_id = $scoutPath['scout_path_id'];
            } else if ($last_scout_path_id != $scoutPath['scout_path_id']) {
                $result += [$last_scout_path_id => $array];
                $array = array();
                $last_scout_path_id = $scoutPath['scout_path_id'];

            }
            $array[] = $scoutPath;
        }

        $result += [$last_scout_path_id => $array];
        return $result;
    }

    public function getUnverifiedScoutPaths($user_id, $position_id): array
    {
        if ($position_id >= 4) {
            return $this->completedTasks->getAllUsersUnverifiedScoutPath($user_id, true);
        } else {
            return $this->completedTasks->getAllUsersUnverifiedScoutPath($user_id);
        }
    }

    public function getPointsForCompletedTask($task_id, $user_id): int
    {
        $result = $this->completedTasks->getUsersTask($task_id, $user_id);
        if (!empty($result)) {
            return $result['points'];
        }
        return 0;
    }

    public function isTaskVerified($task_id, $user_id): bool
    {
        $result = $this->completedTasks->getUsersTask($task_id, $user_id);
        if (!empty($result) && $result['verified'] == 1) {
            return true;
        }
        return false;
    }

    public function isTaskInCompletedTasks($task_id, $user_id): bool
    {
        $result = $this->completedTasks->getUsersTask($task_id, $user_id);
        if (!empty($result)) {
            return true;
        }
        return false;
    }

    public function canUncheckTask($task_id, $user_id, $my_position_id): bool
    {
        $result = $this->completedTasks->getUsersTask($task_id, $user_id);
        if (!empty($result) && $result['verified'] == 1 && $my_position_id < $result['position_id']) {
            return false;
        }
        return true;
    }

    /**
     * @param $task_id
     * @param $user_id
     * @return bool
     * @throws Exception
     */
    public function isTaskUnverified($task_id, $user_id): bool
    {
        $result = $this->completedTasks->getUsersTask($task_id, $user_id);
        if (!empty($result) && $result['verified'] == 0) {
            return true;
        }
        return false;
    }

    public function submitTaskToUser($task_id, $points): bool
    {
        $users_task = $this->completedTasks->getUsersTask($task_id, $_SESSION['view_users_task_id']);
        $task = $scoutPathService->getScoutPathTasks($task_id) ?? $meritBadgeService->getMeritBadgeTask($task_id);

        if (!empty($users_task) && $users_task['verified'] == 1 && $users_task['points'] == null
            && $points != "" && $points > 0 && $_SESSION['position_id'] >= $task['position_id']) {
            if ($this->completedTasks->updateTask($task_id, $_SESSION['view_users_task_id'], 'points', $points) &&
                $this->completedTasks->updateTask($task_id, $_SESSION['view_users_task_id'], 'verified', 1)) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * @param $task_id
     * @param $scoutPathService
     * @param $meritBadgeService
     * @return bool
     * @throws Exception
     */
    public function addTaskToUser(object $task, string $points): bool
    {
        $user_id = $_SESSION['view_users_task_id'];
        $position_id = $_SESSION['position_id'];

        $users_task = $this->completedTasks->getUsersTask($task->id, $user_id);

        if (!empty($users_task)) {
            if ($_SESSION['position_id'] < $task->position_id) {
                throw new Exception("Forbidden to update task");
            } else if (property_exists($task, 'points') && $task->points == null) {
                if ($points != "" && $points > 0) {
                    if ($this->completedTasks->updateTask($task->id, $user_id, "points", $points) &&
                        $this->completedTasks->updateTask($task->id, $user_id, 'verified', 1)) {
                        return true;
                    }
                    throw new Exception("Failed to update task");
                }
                throw new Exception("Wrong input for points");
            } else {
                if ($this->completedTasks->updateTask($task->id, $user_id, 'verified', 1)) {
                    return true;
                }
                throw new Exception("Failed to update task");
            }
        }

        if (property_exists($task, 'points') && $task->points == null) {
            if ($points != "" && $points > 0 && $_SESSION['position_id'] >= $task->position_id) {
                if ($this->completedTasks->addTask($task->id, $user_id, $points, 1)) {
                    return true;
                }
                throw new Exception("Failed to add task to user");
            } else {
                if ($this->completedTasks->addTask($task->id, $user_id, null, 0)) {
                    return false;
                }
                throw new Exception("Failed to add task to user");
            }
        }

        $points = $task->points ?? null;

        if ($position_id >= $task->position_id) {
            if ($this->completedTasks->addTask($task->id, $user_id, $points, 1)) {
                return true;
            }
            throw new Exception("Failed to add task to user");
        } else {
            if ($this->completedTasks->addTask($task->id, $user_id, $points, 0)) {
                return false;
            }
            throw new Exception("Failed to add task to user");
        }
    }

    public function deleteTaskFromUser(object $task): bool
    {
        $user_id = $_SESSION['view_users_task_id'];
        $position_id = $_SESSION['position_id'];

        if ($position_id >= $task->position_id || !$this->isTaskVerified($task->id, $user_id)) {
            return $this->completedTasks->deleteTask($task->id, $user_id);
        } else {
            throw new Exception("Forbidden to delete verified task");
        }
    }
}

?>