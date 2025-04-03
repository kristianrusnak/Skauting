<?php

namespace Task\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

class CompletedTasksManager
{
    //getUsersTask
    public function getTaskByTaskIdAndUserId(int $task_id, int $user_id): null|object
    {
        $result = DB::table("completed_tasks")
            ->where('task_id', $task_id)
            ->where('user_id', $user_id)
            ->first();

        if (empty($result)) {
            return null;
        }

        return $result;
    }

    public function getAllUnverifiedTasksByUserId(int $user_id): array
    {
        $result = DB::table("completed_tasks")
            ->where('user_id', $user_id)
            ->where('verified', 0)
            ->get()
            ->toArray();

        if (empty($result)) {
            return array();
        }

        return $result;
    }

    public function getAllVerifiedTasksByUserId(int $user_id): array
    {
        $result = DB::table("completed_tasks")
            ->where('user_id', $user_id)
            ->where('verified', 1)
            ->get()
            ->toArray();

        if (empty($result)) {
            return array();
        }

        return $result;
    }

    //addTask
    public function add($task_id, $user_id, $points, $verified): bool
    {
        $data = [
            'task_id' => $task_id ?? 0,
            'user_id' => $user_id ?? 0,
            'points' => $points ?? null,
            'verified' => $verified ?? 0
        ];

        if ($data['task_id'] <= 0 || $data['user_id'] <= 0) {
            return false;
        }

        return DB::table("completed_tasks")
            ->insert($data);
    }

    //deleteTask
    public function remove(int $task_id, int $user_id): int
    {
        return DB::table("completed_tasks")
            ->where('task_id', $task_id)
            ->where('user_id', $user_id)
            ->delete();
    }

    //updateTask
    public function update(int $task_id, int $user_id, string $row, mixed $newValue): int
    {
        return DB::table("completed_tasks")
            ->where('task_id', $task_id)
            ->where('user_id', $user_id)
            ->update([
                $row => $newValue
            ]);
    }
}