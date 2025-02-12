<?php

class TaskManager
{
    /**
     * Connection to the database
     *
     * @var DatabaseService
     */
    private DatabaseService $database;

    /**
     * @param $database
     * @throws Exception
     */
    function __construct($database){
        $this->database = $database;
    }

    /**
     * Adds new task to a database
     * If successful function returns id of added task
     * If not function returns false
     *
     * @param $name
     * @param $order
     * @param $task
     * @param $position_id
     * @return false|int
     */
    public function addTask($order, $task, $position_id): false|int
    {
        $this->database->setSql("INSERT INTO tasks (order, task, position_id) VALUES ('$order', '$task', '$position_id')");
        $this->database->execute();
        if ($this->database->getResult()) {
            return $this->database->getAutoIncrement();
        }
        return false;
    }

    /**
     * Deletes task from database based on given id
     * If successful function returns true
     * If not function returns false
     *
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function deleteTask($id): bool
    {
        $this->database->setSql("DELETE FROM tasks WHERE id = '$id'");
        $this->database->execute();
        if ($this->database->getResult()) {
            return true;
        }
        return false;
    }


    /**
     * Updates task
     * If successful function will return true
     * If not function will return false
     *
     * @param $id
     * @param $row
     * @param $newValue
     * @return bool
     */
    public function updateTask($id, $row, $newValue): bool
    {
        $this->database->setSql("UPDATE tasks SET '$row' = '$newValue' WHERE id = '$id'");
        $this->database->execute();
        if ($this->database->getResult()) {
            return true;
        }
        return false;
    }
}

?>