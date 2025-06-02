<?php

namespace Task\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

class MatchTaskManager
{
    public function getAllByTaskId(int $task_id): array
    {
        return DB::table("matched_tasks")
            ->select("match_task_id")
            ->where("task_id", $task_id)
            ->get()
            ->toArray();
    }

    public function embed(int $task_id, string $text): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            shell_exec("start /B python " . dirname(__DIR__, 4) . "\\python\\embedNewTask.py " . escapeshellarg($task_id) . " " . escapeshellarg($text) . " > NUL 2>&1");
        }
        else {
            shell_exec("python " . dirname(__DIR__, 4) . "/python/embedNewTask.py " . escapeshellarg($task_id) . " " . escapeshellarg($text) . " > /dev/null 2>&1 &");
        }
    }

    public function add(int $task_id, int $match_task_id): bool
    {
        return DB::table("matched_tasks")
            ->insert([
                "task_id" => $task_id,
                "match_task_id" => $match_task_id
            ]);
    }

    public function delete(string $row, int $task_id): bool
    {
        return DB::table("matched_tasks")
            ->where($row, $task_id)
            ->delete();
    }

    public function update(string $row, int $task_id, int $new_value): bool
    {
        return DB::table("matched_tasks")
            ->where($row, $task_id)
            ->update([
                $row => $new_value
            ]);
    }
}