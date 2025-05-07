<?php

namespace Task\Service;

require_once dirname(__DIR__) . '/Manager/CommentManager.php';

use Task\Manager\CommentManager as CommentManager;

class CommentService
{
    private CommentManager $comment;

    public function __construct()
    {
        $this->comment = new CommentManager();
    }

    public function get(int $user_id, int $task_id): null|object
    {
        return $this->comment->get($user_id, $task_id);
    }

    public function addOrUpdate(int $user_id, int $task_id, string $comment): bool
    {
        if ($this->comment->get($user_id, $task_id)) {
            return $this->comment->update($user_id, $task_id, $comment);
        } else {
            return $this->comment->add($user_id, $task_id, $comment);
        }
    }

    public function remove(int $user_id, int $task_id): bool
    {
        return $this->comment->remove($user_id, $task_id);
    }
}