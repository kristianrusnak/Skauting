<?php

namespace Task\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use PDOException;

use Illuminate\Database\Capsule\Manager as DB;

class TaskManager
{
    /**
     * Adds new task to a database
     * If successful function returns id of added task
     * If not function returns 0
     *
     * @return int
     */
    protected function _add(array $newTask): int
    {
        $task = $newTask['task'] ?? "";
        $name = $newTask['name'] ?? null;
        $order = $newTask['order'] ?? 1;
        $position_id = $newTask['position_id'] ?? 3;

        if (empty($task)) {
            return 0;
        }

        $data = [
            'name' => $name,
            'task' => $task,
            'order' => $order,
            'position_id' => $position_id
        ];

        return DB::table("tasks")
            ->insertGetId($data);
    }

    /**
     * Deletes task from database based on given id
     * If successful function returns true
     * If not function returns false
     *
     * @param int $id
     * @return int
     */
    protected function _delete(int $id): int
    {
        return DB::table("tasks")
            ->where('id', $id)
            ->delete();
    }


    /**
     * Updates task
     * If successful function will return true
     * If not function will return false
     *
     * @param int $id
     * @param string $row
     * @param string|int $newValue
     * @return int
     */
    protected function _update(int $id, string $row, mixed $newValue): int
    {
        try {
            return DB::table("tasks")
                ->where('id', $id)
                ->update([
                    $row => $newValue
                ]);
        }
        catch (PDOException) {
            return 0;
        }
    }
}

?>