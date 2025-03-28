<?php

namespace Task\Service;

require_once dirname(__DIR__) . '/Manager/MatchTaskManager.php';

use Task\Manager\MatchTaskManager as MatchTask;

class MatchTaskService
{
    private MatchTask $matchTask;

    public function __construct()
    {
        $this->matchTask = new MatchTask();
    }

    public function getMatchTask($task_id): array
    {
        return $this->matchTask->getAllByTaskId($task_id);
    }

    public function embedTask($task_id, $task): void
    {
        $this->matchTask->embed($task_id, $task);
    }
}