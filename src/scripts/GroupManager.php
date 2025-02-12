<?php

class groupManager
{
    /**
     * @var DatabaseService
     */
    private DatabaseService $database;

    /**
     * @param $database
     */
    function __construct($database)
    {
        $this->database = $database;
    }

    /**
     * @param $leader_id
     * @param $user_id
     * @return bool
     * @throws Exception
     */
    function addGroup($leader_id, $user_id): bool
    {
        $this->database->setSql('INSERT INTO groups (leader_id, user_id) VALUES ('.$leader_id.', '.$user_id.')');
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            return true;
        }
        return false;
    }

    /**
     * @param $leader_id
     * @param $user_id
     * @return bool
     * @throws Exception
     */
    function deleteGroup($leader_id, $user_id): bool
    {
        $this->database->setSql('DELETE FROM groups WHERE leader_id = '.$leader_id.' AND user_id = '.$user_id);
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            return true;
        }
        return false;
    }

    /**
     * @param $leader_id
     * @param $user_id
     * @param $row
     * @param $newValue
     * @return bool
     * @throws Exception
     */
    function updateGroup($leader_id, $user_id, $row, $newValue): bool
    {
        $this->database->setSql('UPDATE groups SET '.$row.' = '.$newValue.' WHERE leader_id = '.$leader_id.' AND user_id = '.$user_id);
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            return true;
        }
        return false;
    }
}