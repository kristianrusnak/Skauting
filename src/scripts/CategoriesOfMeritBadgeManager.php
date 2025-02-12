<?php

class CategoriesOfMeritBadgeManager
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
    private array $categories = array();

    /**
     * @param $database
     * @throws Exception
     */
    function __construct($database)
    {
        $this->database = $database;
        $this->fetchAllCategories();
    }

    /**
     * Retrieves all categories from database and store them in $categories as array
     *
     * @return void
     * @throws Exception
     */
    private function fetchAllCategories(): void
    {
        $this->database->setSql("SELECT * FROM categories_of_merit_badges");
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->categories[] = array('id' => $row['id'],
                                            'name' => $row['name']);
            }
        }
    }

    /**
     * Returns array with all categories where key is $id of category and value is a name of category
     *
     * @return array
     */
    public function getAllCategories(): array
    {
        return $this->categories;
    }

    /**
     * Returns name of category based on given $id
     *
     * @param $id
     * @return array
     */
    public function getCategory($id): array
    {
        foreach ($this->categories as $category) {
            if ($category['id'] == $id) {
                return $category;
            }
        }
        return array();
    }

    /**
     * Adds new category of merit badge into database
     * If successful function returns id of added category
     * if not function returns false
     *
     * @param $name
     * @return false|integer
     * @throws Exception
     */
    public function addCategory($name): false|int
    {
        $this->database->setSql("INSERT INTO categories_of_merit_badges (name) VALUES ('$name')");
        $this->database->execute();
        if ($this->database->getResult()) {
            $this->fetchAllCategories();
            return $this->database->getAutoIncrement();
        }
        return false;
    }

    /**
     * Deletes category based on given id
     * If successful function returns true
     * If not function returns false
     *
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function deleteCategory($id): bool
    {
        $this->database->setSql("DELETE FROM categories_of_merit_badges WHERE id = '$id'");
        $this->database->execute();
        if ($this->database->getResult()) {
            $this->fetchAllCategories();
            return true;
        }
        return false;
    }

    /**
     * Updates category
     * if successful function returns true
     * If not function returns false
     *
     * @param $id
     * @param $row
     * @param $newValue
     * @return bool
     * @throws Exception
     */
    public function updateCategory($id, $row, $newValue): bool
    {
        $this->database->setSql("UPDATE categories_of_merit_badges SET '$row' = '$newValue' WHERE id = '$id'");
        $this->database->execute();
        if ($this->database->getResult()) {
            $this->fetchAllCategories();
            return true;
        }
        return false;
    }
}
