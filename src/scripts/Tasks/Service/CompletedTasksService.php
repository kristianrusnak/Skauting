<?php

namespace Task\Service;

require_once dirname(__DIR__) . '/Manager/CompletedTasksManager.php';
require_once dirname(__DIR__, 2) . '/MeritBadge/Service/MeritBadgeService.php';
require_once dirname(__DIR__, 2) . '/ScoutPath/Service/ScoutPathService.php';

use Exception;
use Task\Manager\CompletedTasksManager as CompletedTasksManager;
use MeritBadge\Service\MeritBadgeService as MeritBadgeService;
use ScoutPath\Service\ScoutPathService as ScoutPathService;

class CompletedTasksService
{
    private CompletedTasksManager $completedTasks;

    private MeritBadgeService $badges;

    private ScoutPathService $paths;

    function __construct()
    {
        $this->completedTasks = new CompletedTasksManager();
        $this->badges = new MeritBadgeService();
        $this->paths = new ScoutPathService();
    }

    public function getUnstartedTasksFromMeritMadge(int $merit_badge_id, int $level_id): array
    {
        $tasks = $this->badges->getTasksByMeritBadgeIdAndLevelId($merit_badge_id, $level_id);
        $all_users_tasks = $this->completedTasks->getAllTasksByUserId($_SESSION['view_users_task_id']);
        $all_users_tasks_id = array_map(fn($task) => $task->task_id, $all_users_tasks);

        return array_filter($tasks, fn($task) => !in_array($task->task_id, $all_users_tasks_id));
    }

    public function getUnverifiedTasksFromMeritBadge(int $merit_badge_id, int $level_id): array
    {
        $tasks = $this->badges->getTasksByMeritBadgeIdAndLevelId($merit_badge_id, $level_id);
        $all_users_tasks = $this->completedTasks->getAllUnverifiedTasksByUserId($_SESSION['view_users_task_id']);
        $all_users_tasks_id = array_map(fn($task) => $task->task_id, $all_users_tasks);

        return array_filter($tasks, fn($task) => in_array($task->task_id, $all_users_tasks_id));
    }

    public function getUnstartedTasksFromScoutPath(int $chapter_id): array
    {
        $tasks = $this->paths->getTasksFromChapter($chapter_id);
        $all_users_tasks = $this->completedTasks->getAllTasksByUserId($_SESSION['view_users_task_id']);
        $all_users_tasks_id = array_map(fn($task) => $task->task_id, $all_users_tasks);

        return array_filter($tasks, fn($task) => !in_array($task->task_id, $all_users_tasks_id));
    }

    public function getUnverifiedTasksFromScoutPath(int $chapter_id): array
    {
        $tasks = $this->paths->getTasksFromChapter($chapter_id);
        $all_users_tasks = $this->completedTasks->getAllUnverifiedTasksByUserId($_SESSION['view_users_task_id']);
        $all_users_tasks_id = array_map(fn($task) => $task->task_id, $all_users_tasks);

        return array_filter($tasks, fn($task) => in_array($task->task_id, $all_users_tasks_id));
    }

    public function getVerifiedTasksFromScoutPath(int $chapter_id): array
    {
        $tasks = $this->paths->getTasksFromChapter($chapter_id);
        $all_users_tasks = $this->completedTasks->getAllVerifiedTasksByUserId($_SESSION['view_users_task_id']);
        $all_users_tasks_id = array_map(fn($task) => $task->task_id, $all_users_tasks);

        return array_filter($tasks, fn($task) => in_array($task->task_id, $all_users_tasks_id));
    }

    public function getVerifiedTasksFromMeritBadge(int $merit_badge_id, int $level_id): array
    {
        $tasks = $this->badges->getTasksByMeritBadgeIdAndLevelId($merit_badge_id, $level_id);
        $all_users_tasks = $this->completedTasks->getAllVerifiedTasksByUserId($_SESSION['view_users_task_id']);
        $all_users_tasks_id = array_map(fn($task) => $task->task_id, $all_users_tasks);

        return array_filter($tasks, fn($task) => in_array($task->task_id, $all_users_tasks_id));
    }

    //returns sum of all verified/completed tasks for given $merit_badge_id and $level_id
    //in progress | total sum
    //if there are not tasks or all tasks have been done, function returns empty array
    //getMeritBadgesInProgress
    public function getUsersProgressPointsForMeritBadge(int $user_id, int $merit_badge_id, int $level_id): array
    {
        $tasks = array_map(fn($task) => $task->task_id, $this->badges->getTasksByMeritBadgeIdAndLevelId($merit_badge_id, $level_id));
        $all_users_tasks = array_map(fn($task) => $task->task_id, $this->completedTasks->getAllVerifiedTasksByUserId($user_id));

        $users_tasks = array_intersect($all_users_tasks, $tasks);

        if (empty($users_tasks) || count($users_tasks) == count($tasks)) {
            return array();
        }

        return [
            'in_progress' => count($users_tasks),
            'total' => count($tasks),
            'icon' => "task"
        ];
    }

    //getUnverifiedMeritBadges
    public function hasUserUnverifiedMaritBadgeTasks(int $user_id, int $merit_badge_id, int $level_id): bool
    {
        $tasks = array_map(fn($task) => $task->task_id, $this->badges->getTasksByMeritBadgeIdAndLevelId($merit_badge_id, $level_id));
        $all_users_tasks = array_map(fn($task) => $task->task_id, $this->completedTasks->getAllUnverifiedTasksByUserId($user_id));

        $users_tasks = array_intersect($all_users_tasks, $tasks);

        if (empty($users_tasks)) {
            return false;
        }

        return true;
    }

    //getScoutPathsInProgress
    public function getUsersProgressPointsForScoutPathWithOneTypeOfPoints(int $user_id, int $scout_path_id): array
    {
        $scout_path = $this->paths->getScoutPath($scout_path_id);
        $rp = $this->paths->getRequiredByScoutPathId($scout_path_id);
        $tasks = $this->paths->getTasksByScoutPathId($scout_path_id);
        $tasks_id = array_map(fn($task) => $task->task_id, $tasks);

        $all_users_tasks = $this->completedTasks->getAllVerifiedTasksByUserId($user_id);
        $all_users_tasks_id = array_map(fn($task) => $task->task_id, $all_users_tasks);

        $users_tasks_id = array_intersect($all_users_tasks_id, $tasks_id);

        if (empty($users_tasks_id)) {
            return array();
        }

        $users_tasks = array_filter($all_users_tasks, fn($task) => in_array($task->task_id, $users_tasks_id));

        $total = $scout_path->required_points ?? 0;
        $in_progress = array_reduce($users_tasks, fn($sum, $item) => $sum + $item->points, 0);

        $mandatory_tasks = array_filter($tasks, fn($task) => $task->mandatory == 1);
        $mandatory_tasks_id = array_map(fn($task) => $task->task_id, $mandatory_tasks);

        if ($in_progress >= $total && $this->areAllMandatoryTasksCompleted($users_tasks_id, $mandatory_tasks_id)) {
            return array();
        }

        return [
            'in_progress' => $in_progress,
            'total' => $total,
            'icon' => $rp->icon ?? "task"
        ];
    }

    public function getUsersProgressPointsForScoutPathWithFourTypesOfPoints(int $user_id, int $scout_path_id, int $area_id): array
    {
        $rp = $this->paths->getRequired($scout_path_id, $area_id);
        $tasks = $this->paths->getTasksByScoutPathIdAndAreaId($scout_path_id, $area_id);
        $tasks_id = array_map(fn($task) => $task->task_id, $tasks);
        $total = $rp->required_points ?? 0;

        $all_users_tasks = $this->completedTasks->getAllVerifiedTasksByUserId($user_id);
        $all_users_tasks_id = array_map(fn($task) => $task->task_id, $all_users_tasks);

        $users_tasks_id = array_intersect($all_users_tasks_id, $tasks_id);

        if (empty($users_tasks_id)) {
            return [
                'in_progress' => 0,
                'total' => $total,
                'icon' => $rp->icon ?? "task",
                'completed' => false
            ];
        }

        $users_tasks = array_filter($all_users_tasks, fn($task) => in_array($task->task_id, $users_tasks_id));

        $in_progress = array_reduce($users_tasks, fn($sum, $item) => $sum + $item->points, 0);

        $mandatory_tasks = array_filter($tasks, fn($task) => $task->mandatory == 1);
        $mandatory_tasks_id = array_map(fn($task) => $task->task_id, $mandatory_tasks);

        $data = [
            'in_progress' => $in_progress,
            'total' => $total,
            'icon' => $rp->icon ?? "task",
            'completed' => false
        ];

        if ($in_progress >= $total && $this->areAllMandatoryTasksCompleted($users_tasks_id, $mandatory_tasks_id)) {
            $data['completed'] = true;
        }

        return $data;
    }

    private function areAllMandatoryTasksCompleted(array $all_user_tasks_id, array $all_mandatory_tasks_id): bool
    {
        return empty(array_diff($all_mandatory_tasks_id, $all_user_tasks_id));
    }

    //getUnverifiedScoutPaths
    public function hasUserUnverifiedScoutPathTasksForPatrolLeader(int $user_id, int $scout_path_id): bool
    {
        $tasks = $this->paths->getTasksByScoutPathId($scout_path_id);
        $tasks_id = array_map(fn($task) => $task->task_id, $tasks);

        $all_users_tasks = $this->completedTasks->getAllUnverifiedTasksByUserId($user_id);
        $all_users_tasks_id = array_map(fn($task) => $task->task_id, $all_users_tasks);

        $users_tasks_id = array_intersect($all_users_tasks_id, $tasks_id);

        if (empty($users_tasks_id)) {
            return false;
        }

        $users_tasks = array_filter($tasks, fn($task) => in_array($task->task_id, $users_tasks_id));

        return !empty(array_filter($users_tasks, fn($task) => $task->position_id >= 2 && $task->position_id <= 3));
    }

    public function hasUserUnverifiedScoutPathTasksForLeader(int $user_id, int $scout_path_id): bool
    {
        $tasks = $this->paths->getTasksByScoutPathId($scout_path_id);
        $tasks_id = array_map(fn($task) => $task->task_id, $tasks);

        $all_users_tasks = $this->completedTasks->getAllUnverifiedTasksByUserId($user_id);
        $all_users_tasks_id = array_map(fn($task) => $task->task_id, $all_users_tasks);

        $users_tasks_id = array_intersect($all_users_tasks_id, $tasks_id);

        if (empty($users_tasks_id)) {
            return false;
        }

        $users_tasks = array_filter($tasks, fn($task) => in_array($task->task_id, $users_tasks_id));

        return !empty(array_filter($users_tasks, fn($task) => $task->position_id >= 4));
    }

    public function getPointsForCompletedTask(int $task_id, int $user_id): int
    {
        $task = $this->completedTasks->getTaskByTaskIdAndUserId($task_id, $user_id);

        if (empty($task) || empty($task->points)) {
            return 0;
        }

        return $task->points;
    }

    public function isTaskVerified(int $task_id, int $user_id): bool
    {
        $task = $this->completedTasks->getTaskByTaskIdAndUserId($task_id, $user_id);

        if (empty($task) || $task->verified == 0) {
            return false;
        }

        return true;
    }

    public function isTaskUnverified(int $task_id, int $user_id): bool
    {
        $task = $this->completedTasks->getTaskByTaskIdAndUserId($task_id, $user_id);

        if (empty($task) || $task->verified == 1) {
            return false;
        }

        return true;
    }

    public function isTaskInCompletedTasks(int $task_id, int $user_id): bool
    {
        $task = $this->completedTasks->getTaskByTaskIdAndUserId($task_id, $user_id);

        if (empty($task)) {
            return false;
        }

        return true;
    }

    public function canUncheckTask(int $task_id, int $user_id, int $position_id): bool
    {
        $task = $this->badges->getTask($task_id) ?? $this->paths->getTask($task_id);
        $users_task = $this->completedTasks->getTaskByTaskIdAndUserId($task_id, $user_id);

        if (!empty($users_task) && $position_id < $task->position_id && $users_task->verified == 1) {
            return false;
        }

        return true;
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

        $users_task = $this->completedTasks->getTaskByTaskIdAndUserId($task->id, $user_id);

        if (!empty($users_task)) {
            if ($_SESSION['position_id'] < $task->position_id) {
                throw new Exception("Forbidden to update task");
            } else if (property_exists($task, 'points') && $task->points == null) {
                if ($points != "" && $points > 0) {
                    $this->completedTasks->update($task->id, $user_id, "points", $points);
                    $this->completedTasks->update($task->id, $user_id, 'verified', 1);
                    return true;
                }
                throw new Exception("Wrong input for points");
            } else {
                if ($this->completedTasks->update($task->id, $user_id, 'verified', 1)) {
                    return true;
                }
                throw new Exception("Failed to update task");
            }
        }

        if (property_exists($task, 'points') && $task->points == null) {
            if ($points != "" && $points > 0 && $_SESSION['position_id'] >= $task->position_id) {
                if ($this->completedTasks->add($task->id, $user_id, $points, 1)) {
                    return true;
                }
                throw new Exception("Failed to add task to user");
            } else {
                if ($this->completedTasks->add($task->id, $user_id, null, 0)) {
                    return false;
                }
                throw new Exception("Failed to add task to user");
            }
        }

        $points = $task->points ?? null;

        if ($position_id >= $task->position_id) {
            if ($this->completedTasks->add($task->id, $user_id, $points, 1)) {
                return true;
            }
            throw new Exception("Failed to add task to user");
        } else {
            if ($this->completedTasks->add($task->id, $user_id, $points, 0)) {
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
            return $this->completedTasks->remove($task->id, $user_id);
        } else {
            throw new Exception("Forbidden to delete verified task");
        }
    }
}

?>