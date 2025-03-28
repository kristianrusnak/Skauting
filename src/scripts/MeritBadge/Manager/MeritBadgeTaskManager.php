<?php

namespace MeritBadge\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/tasks/manager/TaskManager.php';

use Illuminate\Database\Capsule\Manager as DB;
use Task\Manager\TaskManager as TaskManager;

class MeritBadgeTaskManager extends TaskManager
{
    private object $tasks;

    function __construct()
    {
        $this->fetch();
    }

    private function fetch(): void
    {
        $this->tasks = DB::table('merit_badge_tasks as mbt')
            ->join('tasks as t', 'mbt.task_id', '=', 't.id')
            ->orderBy('mbt.merit_badge_id')
            ->orderBy('mbt.level_id')
            ->orderBy('t.task')
            ->get();
    }

    public function getAll(): array
    {
        return $this->tasks->toArray();
    }

    function getAllByMeritBadgeId(int $merit_badge_id): array
    {
        return $this->tasks
            ->where('merit_badge_id', $merit_badge_id)
            ->toArray();
    }

    function getAllByMeritBadgeIdAndLevelId(int $merit_badge_id, int $level_id): array
    {
        return $this->tasks
            ->where('merit_badge_id', $merit_badge_id)
            ->where('level_id', $level_id)
            ->toArray();
    }

    public function getByTaskId(int $task_id): null|object
    {
        $result = $this->tasks
            ->where('id', $task_id)
            ->first();

        if ($result) {
            return $result;
        }
        return null;
    }

    public function getLowestTaskLevelInMeritBadgeImage(int $merit_badge_id): null|object
    {
        $result = $this->tasks
            ->where('merit_badge_id', $merit_badge_id)
            ->first();

        if ($result) {
            return $result;
        }
        return null;
    }

    public function add(array $task): int
    {
        $id = TaskManager::_add($task);
        $merit_badge_id = $task['merit_badge_id'] ?? 0;
        $level_id = $task['level_id'] ?? 0;

        if ($id <= 0 || $merit_badge_id <= 0 || $level_id <= 0) {
            return 0;
        }

        $data = [
            'task_id' => $id,
            'merit_badge_id' => $merit_badge_id,
            'level_id' => $level_id
        ];

        DB::table('merit_badge_tasks')
            ->insert($data);

        return $id;
    }

    public function remove(int $id): int
    {
        $effected = TaskManager::_delete($id);

        $this->fetch();

        return $effected;
    }

    public function update(int $id, string $row, string|int $newValue): int
    {
        $effected = TaskManager::_update($id, $row, $newValue);

        $this->fetch();

        return $effected;
    }
}

?>