<?php

class AdditionalInformationAboutMeritBadgeManager
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
    private array $information = array();

    /**
     * @param $database
     * @throws Exception
     */
    function __construct($database)
    {
           $this->database = $database;
           $this->fetchInformation();
    }

    /**
     * Retrieves all additional information about merit badges from database and store them in $information as array
     *
     * @return void
     * @throws Exception
     */
    private function fetchInformation(): void
    {
        $this->database->setSql('SELECT * FROM additional_information ORDER BY id ASC');
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->information[] = array('id' => $row['id'],
                                            'before' => $row['before'],
                                            'between' => $row['between'],
                                            'after' => $row['after']);
            }
        }
    }

    /**
     * Returns all additional information from all merit badges
     *
     * @return array
     */
    public function getAllInformation(): array
    {
        return $this->information;
    }

    /**
     * Returns all additional information base on given id
     *
     * @param $id
     * @return array
     */
    public function getInformation($id): array
    {
        foreach ($this->information as $information) {
            if ($information['id'] == $id) {
                return $information;
            }
        }
        return array();
    }

    /**
     * Adds given information into database
     * If successful function returns id of added information
     * If not function return false
     *
     * @param $before
     * @param $between
     * @param $after
     * @return false|integer
     * @throws Exception
     */
    public function addInformation($before, $between, $after): false|int
    {
        $this->database->setSql("INSERT INTO additional_information (before, between, after) VALUES ('$before', '$between', '$after')");
        $this->database->execute();
        if ($this->database->getResult()) {
            $this->fetchInformation();
            return $this->database->getAutoIncrement();
        }
        return false;
    }

    /**
     * Deletes additional information from database based on given $id
     * If successful function returns true
     * If not function return false
     *
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function deleteInformation($id): bool
    {
        $this->database->setSql("DELETE FROM additional_information WHERE id = '$id'");
        $this->database->execute();
        if ($this->database->getResult()) {
            $this->fetchInformation();
            return true;
        }
        return false;
    }

    /**
     * Updates information
     * If successful function returns true
     * If not function returns false
     *
     * @param $id
     * @param $row
     * @param $newValue
     * @return bool
     * @throws Exception
     */
    public function updateInformation($id, $row, $newValue): bool
    {
        $this->database->setSql("UPDATE additional_information SET '$row' = '$newValue' WHERE id = '$id'");
        $this->database->execute();
        if ($this->database->getResult()) {
            $this->fetchInformation();
            return true;
        }
        return false;
    }
}

?>