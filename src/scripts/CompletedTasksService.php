<?php

class CompletedTasksService
{
    /**
     * @var CompletedTasksManager
     */
    private CompletedTasksManager $completedTasks;

    function __construct($database){
        $this->completedTasks = new CompletedTasksManager($database);
    }

    public function getMeritBadgesInProgress($user_id): array
    {
        return $this->completedTasks->getAllTasksInProgressFromMeritBadge($user_id);
    }

    public function getScoutPathsInProgress($user_id): array
    {
        $scoutPaths = $this->completedTasks->getAllTasksInProgressFromScoutPath($user_id);
        $result = array();

        $last_scout_path_id = '';
        $array = array();
        foreach ($scoutPaths as $scoutPath) {
            if ($last_scout_path_id == ''){
                $last_scout_path_id = $scoutPath['scout_path_id'];
            }
            else if ($last_scout_path_id != $scoutPath['scout_path_id']){
                $result += [$last_scout_path_id => $array];
                $array = array();
                $last_scout_path_id = $scoutPath['scout_path_id'];

            }
            $array[] = $scoutPath;
        }

        $result += [$last_scout_path_id => $array];
        return $result;
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

    /**
     * @param $task_id
     * @param $scoutPathService
     * @param $meritBadgeService
     * @return bool
     * @throws Exception
     */
    public function addTaskToUser($task_id, $scoutPathService, $meritBadgeService): bool
    {
        $user_id = $_COOKIE['view_users_task_id'];
        $position_id = $_COOKIE['position_id'];

        $task = $scoutPathService->getScoutPathTasks($task_id) ?? $meritBadgeService->getMeritBadgeTask($task_id);

        if (array_key_exists('points', $task) && $task['points'] == null) {
            if ($this->completedTasks->addTask($task_id, $user_id, null, false)) {
                return false;
            }
            throw new Exception("Failed to add task to user");
        }

        $points = $task['points'] ?? null;

        if ($position_id >= $task['position_id'] ) {
            if ($this->completedTasks->addTask($task_id, $user_id, $points, true)) {
                return true;
            }
            throw new Exception("Failed to add task to user");
        }
        else {
            if ($this->completedTasks->addTask($task_id, $user_id, $points, false)) {
                return false;
            }
            throw new Exception("Failed to add task to user");
        }
    }

    public function deleteTaskFromUser($task_id, $scoutPathService, $meritBadgeService): bool
    {
        $user_id = $_COOKIE['view_users_task_id'];
        $position_id = $_COOKIE['position_id'];

        $task = $scoutPathService->getScoutPathTasks($task_id) ?? $meritBadgeService->getMeritBadgeTask($task_id);

        if ($position_id >= $task['position_id'] || !$this->isTaskVerified($task_id, $user_id)) {
            return $this->completedTasks->deleteTask($task_id, $user_id);
        }
        else{
            throw new Exception("Forbidden to delete verified task");
        }
    }
}

?>