<?php

class MeritBadgeLevelManager
{
    /**
     * Connection to the database
     *
     * @var DatabaseService
     */
    private DatabaseService $database;

    /**
     * Database content
     *
     * @var array
     */
    private array $levels = array();

    /**
     * @param $database
     * @throws Exception
     */
    function __construct($database){
        $this->database = $database;
        $this->fetchLevels();
    }

    /**
     * Retrieves all levels of merit badges from database and store them in $levels
     *
     * @return void
     * @throws Exception
     */
    private function fetchLevels(): void
    {
        $this->database->setSql("SELECT * FROM levels_of_merit_badge ORDER BY id ASC");
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->levels[] = $row;
            }
        }
    }

    /**
     * Returns all levels from database
     *
     * @return array
     */
    public function getAllLevels(): array
    {
        return $this->levels;
    }

    /**
     * Returns level based on given $id
     *
     * @param $id
     * @return array
     */
    public function getLevel($id): array
    {
        foreach ($this->levels as $level) {
            if ($level['id'] == $id) {
                return $level;
            }
        }
        return array();
    }

    /**
     * Adds new level to a database
     * If successful function returns id of added level
     * If not function returns false
     *
     * @param $name
     * @return false|int
     * @throws Exception
     */
    public function addLevel($name): false|int
    {
        $this->database->setSql("INSERT INTO levels_of_merit_badges (name) VALUES ('$name')");
        $this->database->execute();
        if ($this->database->getResult()) {
            $this->fetchLevels();
            return $this->database->getAutoIncrement();
        }
        return false;
    }

    /**
     * Deletes level from database based on given id
     * If successful function returns true
     * If not function returns false
     *
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function deleteLevel($id): bool
    {
        $this->database->setSql("DELETE FROM levels_of_merit_badges WHERE id = '$id'");
        $this->database->execute();
        if ($this->database->getResult()) {
            $this->fetchLevels();
            return true;
        }
        return false;
    }


    /**
     * Updates level
     * If successful function will return true
     * If not function will return false
     *
     * @param $id
     * @param $row
     * @param $newValue
     * @return bool
     * @throws Exception
     */
    public function updateLevel($id, $row, $newValue): bool
    {
        $this->database->setSql("UPDATE levels_of_merit_badges SET '$row' = '$newValue' WHERE id = '$id'");
        $this->database->execute();
        if ($this->database->getResult()) {
            $this->fetchLevels();
            return true;
        }
        return false;
    }
}

?>