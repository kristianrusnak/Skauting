<?php

class MatchTaskService
{
    private MatchTaskManager $matchTask;

    public function __construct(DatabaseService $database)
    {
        $this->matchTask = new MatchTaskManager($database);
    }

    public function getMatchTask($task_id): array
    {
        return $this->matchTask->get($task_id);
    }

    public function embedTask($task_id, $task): void
    {
        $this->matchTask->embed($task_id, $task);
    }
}