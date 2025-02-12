<?php

class ScoutPathManager
{
    /**
     * @var DatabaseService
     */
    private DatabaseService $database;

    /**
     * @var array
     */
    private array $path = array();

    /**
     * @param $database
     * @throws Exception
     */
    function __construct($database)
    {
        $this->database = $database;
        $this->fetchPath();
    }

    /**
     * @return void
     * @throws Exception
     */
    private function fetchPath(): void
    {
        $this->database->setSql('SELECT * FROM scout_path');
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->path[] = $row;
            }
        }
    }

    /**
     * @return array
     */
    public function getAllPaths(): array
    {
        return $this->path;
    }

    /**
     * @param $path_id
     * @return array
     */
    public function getPath($path_id): array
    {
        foreach ($this->path as $path) {
            if ($path['id'] == $path_id) {
                return $path;
            }
        }
        return array();
    }

    /**
     * @param $name
     * @param $image
     * @param $color
     * @param $required_points
     * @return int|false
     * @throws Exception
     */
    public function addPath($name, $image, $color, $required_points): int|false
    {
        $this->database->setSql('INSERT INTO scout_path (name, image, color, required_points) VALUES ('.$name.', '.$image.', '.$color.', '.$required_points.')');
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            $this->fetchPath();
            return $this->database->getAutoIncrement();
        }
        return false;
    }

    /**
     * @param $path_id
     * @return bool
     * @throws Exception
     */
    public function deletePath($path_id): bool
    {
        $this->database->setSql('DELETE FROM scout_path WHERE id = '.$path_id);
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            $this->fetchPath();
            return true;
        }
        return false;
    }

    /**
     * @param $path_id
     * @param $row
     * @param $newValue
     * @return bool
     * @throws Exception
     */
    public function updatePath($path_id, $row, $newValue): bool
    {
        $this->database->setSql('UPDATE scout_path SET '.$row.' = '.$newValue.' WHERE id = '.$path_id);
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            $this->fetchPath();
            return true;
        }
        return false;
    }
}

?>