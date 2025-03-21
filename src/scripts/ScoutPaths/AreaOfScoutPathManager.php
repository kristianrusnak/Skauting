<?php

class AreaOfScoutPathManager
{
    /**
     * @var databaseService
     */
    private DatabaseService $database;

    /**
     * @var array
     */
    private array $areas = array();

    function __construct($database)
    {
        $this->database = $database;
        $this->fetchAreas();
    }

    /**
     * @return void
     * @throws Exception
     */
    private function fetchAreas(): void
    {
        $this->database->setSql('SELECT * FROM areas_of_progress');
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->areas[] = $row;
            }
        }
    }

    /**
     * @return array
     */
    public function getAllAreas(): array
    {
        return $this->areas;
    }

    /**
     * @param $area_id
     * @return array
     */
    public function getArea($area_id): array
    {
        foreach ($this->areas as $area) {
            if ($area['id'] == $area_id) {
                return $area;
            }
        }
        return array();
    }

    /**
     * @param $name
     * @param $color
     * @return int|false
     * @throws Exception
     */
    public function addArea($name, $color): int|false
    {
        $this->database->setSql('INSERT INTO areas_of_progress (name, color) VALUES ("'.$name.'", '.$color.')');
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            $this->fetchAreas();
            return $this->database->getAutoIncrement();
        }
        return false;
    }

    /**
     * @param $area_id
     * @return bool
     * @throws Exception
     */
    public function deleteArea($area_id): bool
    {
        $this->database->setSql('DELETE FROM areas_of_progress WHERE id = '.$area_id);
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            $this->fetchAreas();
            return true;
        }
        return false;
    }

    /**
     * @param $area_id
     * @param $row
     * @param $newValue
     * @return bool
     * @throws Exception
     */
    public function updateArea($area_id, $row, $newValue): bool
    {
        $this->database->setSql('UPDATE areas_of_progress SET '.$row.' = '.$newValue.' WHERE id = '.$area_id);
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            $this->fetchAreas();
            return true;
        }
        return false;
    }
}

?>