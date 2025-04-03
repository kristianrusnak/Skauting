<?php

namespace MeritBadge\Service;

require_once dirname(__DIR__) . '/Manager/AdditionalInformationAboutMeritBadgeManager.php';
require_once dirname(__DIR__) . '/Manager/CategoriesOfMeritBadgeManager.php';
require_once dirname(__DIR__) . '/Manager/MeritBadgeLevelManager.php';
require_once dirname(__DIR__) . '/Manager/MeritBadgeManager.php';
require_once dirname(__DIR__) . '/Manager/MeritBadgeTaskManager.php';
require_once dirname(__DIR__, 2) . '/Tasks/Manager/MatchTaskManager.php';

use Error;
use Exception;

use MeritBadge\Manager\AdditionalInformationAboutMeritBadgeManager as Information;
use MeritBadge\Manager\CategoriesOfMeritBadgeManager as Categories;
use MeritBadge\Manager\MeritBadgeLevelManager as Levels;
use MeritBadge\Manager\MeritBadgeManager as Badges;
use MeritBadge\Manager\MeritBadgeTaskManager as Tasks;
use Task\Manager\MatchTaskManager as Matches;

class MeritBadgeService
{
    private Information $information;

    private Categories $categories;

    private Levels $levels;

    private Badges $badges;

    private Tasks $tasks;

    private Matches $matches;

    function __construct()
    {
        $this->information = new Information();
        $this->categories = new Categories();
        $this->levels = new Levels();
        $this->badges = new Badges();
        $this->tasks = new Tasks();
        $this->matches = new Matches();
    }

    public function getMeritBadges(): array
    {
        return $this->badges->getAll();
    }

    public function getMeritBadge(int $merit_badge_id): null|object
    {
        return $this->badges->getById($merit_badge_id);
    }

    public function getMeritBadgeByTaskId(int $task_id): null|object
    {
        $task = $this->getTask($task_id);

        if (!empty($task)) {
            $object = $this->getMeritBadge($task->merit_badge_id);
            $object->type = "meritBadge";
            return $object;
        }

        return null;
    }

    public function getTask(int $task_id): null|object
    {
        $object = $this->tasks->getByTaskId($task_id);

        if (empty($object)) {
            return null;
        }

        $object->type = "meritBadge";
        return $object;
    }

    public function getMeritBadgeTask(int $task_id): null|object
    {
        $task = $this->tasks->getByTaskId($task_id);

        if (empty($task)) {
            return null;
        }

        return $task;
    }

    public function getTasksByMeritBadgeIdAndLevelId(int $merit_badge_id, int $level_id): array
    {
        return $this->tasks->getAllByMeritBadgeIdAndLevelId($merit_badge_id, $level_id);
    }

    public function getLevels(): array
    {
        return $this->levels->getAll();
    }

    public function getAllMeritBadgesByCategoryId(int $category_id): array
    {
        return $this->badges->getAllByCategoryId($category_id);
    }

    public function getAllCategories(): array
    {
        return $this->categories->getAll();
    }

    public function isMeritBadgeIdValid($merit_badge_id): bool
    {
        $meritBadge = $this->badges->getById($merit_badge_id);
        if (!empty($meritBadge)) {
            return true;
        }
        return false;
    }

    public function getMeritBadgeName($merit_badge_id): string
    {
         $task = $this->badges->getById($merit_badge_id);
         if (isset($task->name)){
             return $task->name;
         }
         return '';
    }

    public function createNewMeritBadge(array $data): bool
    {
        if ($this->badges->add($data)){
            return true;
        }
        return false;
    }

    public function deleteMeritBadge($merit_badge_id): void
    {
        $tasks = $this->tasks->getAllByMeritBadgeId($merit_badge_id);

        $this->badges->delete($merit_badge_id);

        foreach ($tasks as $task) {
            $this->tasks->remove($task->task_id);
        }
    }

    public function updateMeritBadge(int $merit_badge_id, array $data): int
    {
        $result = 0;

        foreach ($data as $row => $newValue) {
            $result += $this->badges->update($merit_badge_id, $row, $newValue);
        }

        return $result;
    }

    public function createNewMeritBadgeTask(array $task): bool
    {
        try {
            if ($task_id = $this->tasks->add($task)){
                $this->matches->embed($task_id, $task['task']);
                return true;
            }
            return false;
        }
        catch (Exception|Error) {
            return false;
        }
    }

    public function UpdateMeritBadgeTask(int $task_id, string $task): bool
    {
        try {
            return $this->tasks->update($task_id, "task", $task) > 0;
        }
        catch (Exception|Error) {
            return false;
        }
    }

    public function deleteMeritBadgeTask(int $task_id): bool
    {
        try {
            if ($this->tasks->remove($task_id)){
                return true;
            }
            return false;
        }
        catch (Exception|Error) {
            return false;
        }
    }
}