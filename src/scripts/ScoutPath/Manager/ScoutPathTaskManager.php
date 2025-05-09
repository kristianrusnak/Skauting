<?php

namespace ScoutPath\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/Tasks/Manager/TaskManager.php';

use PDOException;

use Illuminate\Database\Capsule\Manager as DB;
use Task\Manager\TaskManager as TaskManager;

class ScoutPathTaskManager extends TaskManager
{
    private object $tasks;

    function __construct()
    {
        $this->fetch();
    }

    private function fetch(): void
    {
        $this->tasks = DB::table('scout_path_tasks as spt')
            ->join('tasks as t', 'spt.task_id', '=', 't.id')
            ->orderBy('spt.chapter_id')
            ->orderByDesc('spt.mandatory')
            ->orderBy('t.name')
            ->get();
    }

    public function getAll(): array
    {
        return $this->tasks->toArray();
    }

    public function getById($id): null|object
    {
        $result = $this->tasks
            ->where('id', $id)
            ->first();

        if ($result) {
            return $result;
        }

        return null;
    }

    public function add(array $newTask): int
    {
        $id = TaskManager::_add($newTask);
        $chapter_id = $newTask['chapter_id'] ?? 0;
        $points = $newTask['points'] ?? null;
        $mandatory = $newTask['mandatory'] ?? 1;

        if ($id <= 0 || $chapter_id <= 0) {
            return 0;
        }

        $data = [
            'task_id' => $id,
            'chapter_id' => $chapter_id,
            'points' => $points,
            'mandatory' => $mandatory
        ];

        DB::table('scout_path_tasks')
            ->insert($data);

        return $id;
    }

    public function remove(int $id): int
    {
        $effected = TaskManager::_delete($id);

        $this->fetch();

        return $effected;
    }

    public function update(int $id, string $row, mixed $newValue): int
    {
        $effected = TaskManager::_update($id, $row, $newValue);

        try {
            $effected += DB::table('scout_path_tasks')
                ->where('task_id', $id)
                ->update([
                    $row => $newValue
                ]);
        }
        catch (PDOException) {}

        $this->fetch();

        return $effected;
    }
}

?>