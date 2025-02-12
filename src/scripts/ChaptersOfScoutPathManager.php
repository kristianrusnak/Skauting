<?php

class ChaptersOfScoutPathManager
{
    /**
     * Database connection
     *
     * @var DatabaseService
     */
    private DatabaseService $database;

    /**
     * Fetch information from database about chapters of scout path
     *
     * @var array
     */
    private array $chapters = array();


    /**
     * Sets database connection and fetches information about chapters of scout path
     *
     * @param $database
     * @throws Exception
     */
    function __construct($database)
    {
        $this->database = $database;
        $this->fetchChapters();
    }

    /**
     * Fetches all information from database about chapters of scout path
     *
     * @return void
     * @throws Exception
     */
    private function fetchChapters(): void
    {
        $this->database->setSql('SELECT * FROM chapters_of_scout_path');
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->chapters[] = $row;
            }
        }
    }

    /**
     * @return array
     */
    public function getAllChapters(): array
    {
        return $this->chapters;
    }

    public function getChapter($chapter_id)
    {
        foreach ($this->chapters as $chapter) {
            if ($chapter['id'] == $chapter_id) {
                return $chapter;
            }
        }
        return array();
    }

    /**
     * @param $name
     * @param $mandatory
     * @param $area_id
     * @param $scout_path_id
     * @return false|int
     * @throws Exception
     */
    public function addChapter($name, $mandatory, $area_id, $scout_path_id): false|int
    {
        $this->database->setSql('INSERT INTO chapters_of_scout_path (name, mandatory, area_id, scout_path_id) VALUES ("'.$name.'", '.$mandatory.', '.$area_id.', '.$scout_path_id.')');
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            $this->fetchChapters();
            return $this->database->getAutoIncrement();
        }
        return false;
    }

    /**
     * @param $chapter_id
     * @return bool
     * @throws Exception
     */
    public function deleteChapter($chapter_id): bool
    {
        $this->database->setSql('DELETE FROM chapters_of_scout_path WHERE id = '.$chapter_id);
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            $this->fetchChapters();
            return true;
        }
        return false;
    }

    /**
     * @param $chapter_id
     * @param $row
     * @param $newValue
     * @return bool
     * @throws Exception
     */
    public function updateChapter($chapter_id, $row, $newValue): bool
    {
        $this->database->setSql('UPDATE chapters_of_scout_path SET '.$row.' = '.$newValue.' WHERE id = '.$chapter_id);
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            $this->fetchChapters();
            return true;
        }
        return false;
    }
}

?>