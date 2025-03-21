<?php

class MatchTaskManager
{
    private DatabaseService $database;

    public function __construct(DatabaseService $database)
    {
        $this->database = $database;
    }

    public function get($task_id): array
    {
        $this->database->setSql("
            SELECT match_task_id
            FROM matched_tasks
            WHERE task_id = $task_id
        ");
        $this->database->execute();
        $result = $this->database->getResult();
        $array = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $array[] = $row;
            }
        }
        return $array;
    }

    public function embed($task_id, $text): void
    {
        shell_exec("/usr/local/bin/python3-intel64 /Applications/XAMPP/xamppfiles/htdocs/Skauting/python/embedNewTask.py " . escapeshellarg($task_id) . " " . escapeshellarg($text) . " > /dev/null 2>&1 &");
    }

    public function add($task_id, $match_task_id): bool
    {
        $this->database->setSql("
            INSERT INTO matched_tasks
                (task_id, match_task_id)
            VALUES 
                ($task_id, $match_task_id)
        ");
        $this->database->execute();
        $result = $this->database->getResult();
        return $result;
    }

    public function delete($row, $task_id): bool
    {
        $this->database->setSql("
            DELETE FROM matched_tasks
            WHERE `$row` = $task_id
        ");
        $this->database->execute();
        $result = $this->database->getResult();
        return $result;
    }

    public function update($row, $task_id, $new_value): bool
    {
        $this->database->setSql("
            UPDATE matched_tasks
            SET `$row` = $new_value
            WHERE `$row` = $task_id
        ");
        $this->database->execute();
        $result = $this->database->getResult();
        return $result;
    }
}