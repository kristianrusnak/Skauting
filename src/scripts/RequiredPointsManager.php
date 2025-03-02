<?php

class RequiredPointsManager
{
    /**
     * @var DatabaseService
     */
    private DatabaseService $database;

    /**
     * @var array
     */
    private array $rp = array();

    /**
     * @param $database
     * @throws Exception
     */
    function __construct($database)
    {
        $this->database = $database;
        $this->fetchRP();
    }

    /**
     * @return void
     * @throws Exception
     */
    private function fetchRP(): void
    {
        $this->database->setSql('SELECT * FROM required_points');
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->rp[] = $row;
            }
        }
    }

    /**
     * @return array
     */
    public function getAllRPs(): array
    {
        return $this->rp;
    }

    /**
     * @param $rp_id
     * @return array
     */
    public function getRP($rp_id): array
    {
        foreach ($this->rp as $rp) {
            if ($rp['id'] == $rp_id) {
                return $rp;
            }
        }
        return array();
    }

    /**
     * @param $required_points
     * @param $type_of_points
     * @param $icon
     * @return bool
     * @throws Exception
     */
    public function addRP($scout_path_id, $area_id, $required_points = null, $type_of_points = "Uloha", $name = "Nova sekcia", $icon = "task"): bool
    {
        if ($required_points == null) {
            $this->database->setSql('
            INSERT INTO required_points 
                (scout_path_id, area_id, type_of_points, name, icon) 
            VALUES 
                (\''.$scout_path_id.'\', \''.$area_id.'\', \''.$type_of_points.'\', \''.$name.'\', \''.$icon.'\')
        ');
        }
        else {
            $this->database->setSql('
            INSERT INTO required_points 
                (scout_path_id, area_id, required_points, type_of_points, name, icon) 
            VALUES 
                (\''.$scout_path_id.'\', \''.$area_id.'\', '.$required_points.', \''.$type_of_points.'\', \''.$name.'\', \''.$icon.'\')
        ');
        }
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            $this->fetchRP();
            return true;
        }
        return false;
    }

    /**
     * @param $scout_path_id
     * @param $area_id
     * @return bool
     * @throws Exception
     */
    public function deleteRP($scout_path_id): bool
    {
        $this->database->setSql("DELETE FROM required_points WHERE scout_path_id = ".$scout_path_id);
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            $this->fetchRP();
            return true;
        }
        return false;
    }

    /**
     * @param $scout_path_id
     * @param $area_id
     * @param $row
     * @param $newValue
     * @return bool
     * @throws Exception
     */
    public function updateRP($scout_path_id, $area_id, $row, $newValue): bool
    {
        $this->database->setSql('UPDATE required_points SET '.$row.' = '.$newValue.' WHERE scout_path_id = '.$scout_path_id.' AND area_id = '.$area_id);
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            $this->fetchRP();
            return true;
        }
        return false;
    }
}

?>