<?php

class MeritBadgeTaskManager
{
    /**
     * Database connection
     *
     * @var DatabaseService
     */
    private DatabaseService $database;

    /**
     * Merit badge tasks array
     *
     * @var array
     */
    private array $tasks = array();

    /**
     * @param $database
     * @throws Exception
     */
    function __construct($database)
    {
        $this->database = $database;
        $this->fetchMeritBadgeTasks();
    }

    /**
     * Fetches data from database
     *
     * @return void
     * @throws Exception
     */
    private function fetchMeritBadgeTasks(): void
    {
        $this->database->setSql("
                SELECT 
                    mbt.task_id,
                    mbt.merit_badge_id,
                    mbt.level_id,
                    t.name AS task_name,
                    t.task,
                    t.position_id,
                    lmb.name AS level_name,
                    lmb.color AS level_color
                FROM merit_badge_tasks AS mbt
                INNER JOIN tasks AS t ON mbt.task_id = t.id
                INNER JOIN levels_of_merit_badge AS lmb ON mbt.level_id = lmb.id
                ORDER BY mbt.merit_badge_id, mbt.level_id, t.order
        ");
        $this->database->execute();
        $result = $this->database->getResult();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->tasks[] = $row;
            }
        }
    }

    /**
     * Returns all merit badge tasks
     *
     * @return array
     */
    public function getAllMeritBadgeTasks(): array
    {
        return $this->tasks;
    }

    /**
     * Returns merit badge task based on given $task_id
     *
     * @param $task_id
     * @return array
     */
    public function getMeritBadgeTask($task_id): array
    {
        foreach ($this->tasks as $task) {
            if ($task['task_id'] == $task_id) {
                return $task;
            }
        }
        return array();
    }

    /**
     * Returns array with all tasks with $merit_badge_id
     *
     * @param $merit_badge_id
     * @return array
     * @throws Exception
     */
    function getAllTasksWithMeritBadge($merit_badge_id): array
    {
        $array = array();
        foreach ($this->tasks as $task) {
            if ($task['merit_badge_id'] == $merit_badge_id) {
                $array[] = $task;
            }
        }
        return $array;
    }

    /**
     * Returns all tasks with given $merit_badge_id and $level
     *
     * @param $merit_badge_id
     * @param $level_id
     * @return array
     * @throws Exception
     */
    function getAllTasksWithMeritBadgeAndLevel($merit_badge_id, $level_id): array
    {
        $array = array();
        foreach ($this->tasks as $task) {
            if ($task['merit_badge_id'] == $merit_badge_id && $task['level_id'] == $level_id) {
                $array += $task;
            }
        }
        return $array;
    }

    /**
     * Checks if task is Merit Badge task based on $task_id
     * If task is merit badge function returns true
     * If not function returns false
     *
     * @param $task_id
     * @return bool
     * @throws Exception
     */
    function contains($task_id): bool
    {
        foreach ($this->tasks as $task) {
            if ($task['task_id'] == $task_id) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns id of the level of merit badge based on $task_id
     * If $task_id is in merit badge tasks, function returns $id on the level if the merit badge
     * If not function returns false
     *
     * @param $task_id
     * @return false|int
     * @throws Exception
     */
    function getLevel($task_id): false|int
    {
        foreach ($this->tasks as $task) {
            if ($task['task_id'] == $task_id) {
                return $task['level_id'];
            }
        }
        return false;
    }

    /**
     * Returns image of the lowest possible level of given merit badge
     *
     * @param $merit_badge_id
     * @return string
     * @throws Exception
     */
    public function getLowestTaskLevelInMeritBadgeImage($merit_badge_id): string
    {
        $this->database->setSql(
            'SELECT *
            FROM merit_badge_tasks AS mbt
            INNER JOIN levels_of_merit_badge AS lom ON mbt.level_id = lom.id
            WHERE mbt.merit_badge_id = '.$merit_badge_id.'
            GROUP BY mbt.level_id ASC
            LIMIT 1'
        );
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc()['image'];
        }
        return '';
    }

    /**
     * Returns id of merit badge based on $task_id
     * If $task_id is in merit badge tasks, function returns $id on the merit badge
     * If not function returns false
     *
     * @param $task_id
     * @return false|int
     * @throws Exception
     */
    function getMeritBadge($task_id): false|int
    {
        foreach ($this->tasks as $task) {
            if ($task['task_id'] == $task_id) {
                return $task['merit_badge_id'];
            }
        }
        return false;
    }

    /**
     * Adds merit badge task into table
     * if successful function returns true
     * If not function returns false
     *
     * @param $task_id
     * @param $merit_badge_id
     * @param $level_id
     * @return bool
     * @throws Exception
     */
    function addMeritBadgeTask($task_id, $merit_badge_id, $level_id): bool
    {
        $this->database->setSql("INSERT INTO merit_badge_tasks (task_id, merit_badge_id, level_id) VALUES ('$task_id', '$merit_badge_id', '$level_id')");
        $this->database->execute();
        if ($this->database->getResult()) {
            $this->fetchMeritBadgeTasks();
            return true;
        }
        return false;
    }
}

?>