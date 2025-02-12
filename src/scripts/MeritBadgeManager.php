<?php

class MeritBadgeManager
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
    private array $meritBadge = array();

    /**
     * @param $database
     * @throws Exception
     */
    public function __construct($database)
    {
        $this->database = $database;
        $this->fetchMeritBadges();
    }

    /**
     * Fetches all merit badges from database and store them in $meritBadge
     *
     * @return void
     * @throws Exception
     */
    private function fetchMeritBadges(): void
    {
        $this->database->setSql(
            "SELECT 
                mb.id,
                mb.name,
                mb.image,
                mb.color,
                cob.name AS category_id,
                mb.additional_information_id
            FROM merit_badges AS mb
            INNER JOIN categories_of_merit_badges AS cob ON mb.category_id = cob.id
            ORDER BY mb.category_id , mb.name "
        );
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->meritBadge[] = $row;
            }
        }
    }

    /**
     * Returns array with information about merit badges
     *
     * @return array
     */
    public function getAllMeritBadges(): array
    {
        return $this->meritBadge;
    }

    /**
     * Returns array with information about merit badge base on $id of the merit badge
     *
     * @param $id
     * @return array
     */
    public function getMeritBadge($id): array
    {
        foreach ($this->meritBadge as $meritBadge) {
            if ($meritBadge['id'] == $id) {
                return $meritBadge;
            }
        }
        return array();
    }

    /**
     * Adds new merit badge into database
     * If successful function returns id of added merit badge
     * If not function returns false
     *
     * @param $name
     * @param $image
     * @param $color
     * @param $category_id
     * @param $additional_information_id
     * @return false|integer
     * @throws Exception
     */
    public function addMeritBadge($name, $image, $color, $category_id, $additional_information_id = null): false|int
    {
        $this->database->setSql("INSERT INTO merit_badges (name, image, color, category_id, additional_information_id) VALUES ('$name', '$image', '$color', '$category_id', '$additional_information_id')");
        $this->database->execute();
        if ($this->database->getResult()) {
            $this->fetchMeritBadges();
            return $this->database->getAutoIncrement();
        }
        return false;
    }

    /**
     * Deletes merit badge based on the $id of the merit badge
     * If successful function return true
     * If not function returns false
     * 
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function deleteMeritBadge($id): bool
    {
        $this->database->setSql("DELETE FROM merit_badges WHERE id = '$id'");
        $this->database->execute();
        if ($this->database->getResult()) {
            $this->fetchMeritBadges();
            return true;
        }
        return false;
    }

    /**
     * Updates merit badge
     * If operation is successful function returns true
     * If not function returns false
     *
     * @param $id
     * @param $row
     * @param $newValue
     * @return bool
     * @throws Exception
     */
    public function updateMeritBadge($id, $row, $newValue): bool
    {
        $this->database->setSql("UPDATE merit_badges SET '$row' = '$newValue' WHERE id = '$id'");
        $this->database->execute();
        if ($this->database->getResult()) {
            $this->fetchMeritBadges();
            return true;
        }
        return false;
    }
}