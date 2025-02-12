<?php

class ScoutPathTaskManager
{
    /**
     * Database connection
     *
     * @var DatabaseService
     */
    private DatabaseService $database;

    /**
     * Tasks array
     *
     * @var array
     */
    private array $tasks = array();

    /**
     * Establishes database communication and fetches information about scout path tasks from database
     *
     * @param $database
     * @throws Exception
     */
    function __construct($database)
    {
        $this->database = $database;
        $this->fetchTasks();
    }

    /**
     * Unsets used variables
     */
    function __destruct()
    {
        unset($this->database);
        unset($this->tasks);
    }

    /**
     * Fetches information about scout path tasks from database
     *
     * @return void
     * @throws Exception
     */
    private function fetchTasks()
    {
        $this->database->setSql('
                SELECT
                    t.id AS task_id,
                    t.name AS task_name,
                    t.task,
                    t.position_id,
                    spt.points,
                    spt.mandatory,
                    csp.id AS chapter_id,
                    csp.name AS chapter_name,
                    csp.scout_path_id,
                    aop.id AS area_id,
                    aop.name AS area_name,
                    aop.color,
                    rp.type_of_points,
                    rp.icon
                FROM scout_path_tasks AS spt
                INNER JOIN tasks AS t ON spt.task_id = t.id
                INNER JOIN chapters_of_scout_path AS csp ON spt.chapter_id = csp.id
                INNER JOIN areas_of_progress AS aop ON aop.id = csp.area_id
                INNER JOIN required_points AS rp ON rp.scout_path_id = csp.scout_path_id AND rp.area_id = csp.area_id
                ORDER BY csp.scout_path_id, csp.area_id, spt.chapter_id, spt.mandatory DESC, t.order
        ');
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->tasks[] = $row;
            }
        }
    }

    /**
     * Returns all tasks from scout paths
     *
     * @return array
     */
    public function getAllTasks(): array
    {
        return $this->tasks;
    }

    /**
     * Returns task based on $task_id
     *
     * @param $task_id
     * @return array
     */
    public function getTask($task_id): array
    {
        foreach ($this->tasks as $task) {
            if ($task['task_id'] == $task_id) {
                return $task;
            }
        }
        return array();
    }

    /**
     * @param $task_id
     * @param $chapter_id
     * @param $points
     * @param $mandatory
     * @return bool
     * @throws Exception
     */
    public function addTask($task_id, $chapter_id, $points, $mandatory): bool
    {
        $this->database->setSql('INSERT INTO scout_path_task (task_id, chapter_id, points, mandatory) VALUES ('.$task_id.', '.$chapter_id.', '.$points.', '.$mandatory.')');
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            $this->fetchTasks();
            return true;
        }
        return false;
    }

    /**
     * @param $task_id
     * @return bool
     * @throws Exception
     */
    public function deleteTask($task_id): bool
    {
        $this->database->setSql('DELETE FROM scout_path_task WHERE task_id = '.$task_id);
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            $this->fetchTasks();
            return true;
        }
        return false;
    }

    /**
     * @param $task_id
     * @param $row
     * @param $newValue
     * @return bool
     * @throws Exception
     */
    public function updateTask($task_id, $row, $newValue): bool
    {
        $this->database->setSql('UPDATE scout_path_task SET '.$row.' = '.$newValue.' WHERE task_id = '.$task_id);
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            $this->fetchTasks();
            return true;
        }
        return false;
    }
}

?>