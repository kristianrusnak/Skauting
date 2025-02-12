<?php

class PositionManager
{
    private DatabaseService $database;
    private array $positions = array();

    /**
     * @param $database
     * @throws Exception
     */
    function __construct($database){
        $this->database = $database;
        $this->fetchAllPositions();
    }

    /**
     * Fetches all positions from database
     *
     * @return void
     * @throws Exception
     */
    private function fetchAllPositions(): void
    {
        $this->database->setSql("SELECT * FROM positions");
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->positions[] = $row;
            }
        }
    }

    /**
     * @return array
     */
    public function getAllPositions(): array
    {
        return $this->positions;
    }

    /**
     * @param $id
     * @return array
     */
    public function getPosition($id): array
    {
        foreach ($this->positions as $position) {
            if ($position['id'] == $id) {
                return $position;
            }
        }
        return array();
    }
}

?>