<?php

namespace Task\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
class CommentManager
{
    public function get(int $user_id, int $task_id): null|object
    {
        $comment = DB::table('comments')
            ->where('user_id', $user_id)
            ->where('task_id', $task_id)
            ->first();

        if ($comment) {
            return $comment;
        }

        return null;
    }

    public function add(int $user_id, int $task_id, string $comment): bool
    {
        if (empty($comment)) {
            return false;
        }

        $data = [
            'user_id' => $user_id,
            'task_id' => $task_id,
            'comment' => $comment,
        ];

        return DB::table('comments')->insert($data);
    }

    public function remove(int $user_id, int $task_id): int
    {
        return DB::table('comments')
            ->where('user_id', $user_id)
            ->where('task_id', $task_id)
            ->delete();
    }

    public function update(int $user_id, int $task_id, string $comment): int
    {
        if (empty($comment)) {
            return false;
        }

        return DB::table('comments')
            ->where('user_id', $user_id)
            ->where('task_id', $task_id)
            ->update([
                'comment' => $comment,
            ]);
    }
}