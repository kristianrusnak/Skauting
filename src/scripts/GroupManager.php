<?php

class groupManager
{
    /**
     * @var DatabaseService
     */
    private DatabaseService $database;

    private array $groups = array();

    private array $leaders = array();

    /**
     * @param $database
     */
    function __construct($database)
    {
        $this->database = $database;
        $this->fetchGroups();
        $this->fetchLeaders();
    }

    public function fetchGroups(): void
    {
        $this->database->setSql('
                SELECT 
                    l.id AS leader_id,
                    l.name AS leader_name,
                    l.email AS leader_email,
                    l.image AS leader_image,
                    l.position_id AS leader_position_id,
                    m.id AS member_id,
                    m.name AS member_name,
                    m.email AS member_email,
                    m.image AS member_image,
                    m.position_id AS member_position_id
                FROM `groups` AS g
                INNER JOIN users AS m ON g.user_id = m.id
                INNER JOIN users AS l ON g.leader_id = l.id
                ORDER BY l.name, m.name
        ');
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->groups[] = $row;
            }
        }
    }

    public function fetchLeaders():void
    {
        $this->database->setSql('
                SELECT 
                    u.id AS leader_id,
                    u.name AS name,
                    u.email AS email,
                    u.image AS image,
                    u.position_id AS position_id
                FROM `groups` AS g
                INNER JOIN users AS u ON g.leader_id = u.id
                GROUP BY u.name
        ');
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->leaders[] = $row;
            }
        }
    }

    public function getAllGroups(): array
    {
        return $this->groups;
    }

    public function getAllLeaders(): array
    {
        return $this->leaders;
    }

    public function getLeader($leader_id): array
    {
        foreach ($this->leaders as $leader) {
            if ($leader['leader_id'] == $leader_id) {
                return $leader;
            }
        }
        return array();
    }

    public function deleteGroup($leader_id, $fetch = false): bool
    {
        $this->database->setSql('DELETE FROM `groups` WHERE leader_id = '.$leader_id);
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            if ($fetch) {
                $this->fetchGroups();
                $this->fetchGroups();
            }
            return true;
        }
        return false;
    }

    public function deleteMemberOfGroup($member_id, $fetch = false): bool
    {
        $this->database->setSql('DELETE FROM `groups` WHERE user_id = '.$member_id);
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            if ($fetch) {
                $this->fetchGroups();
                $this->fetchGroups();
            }
            return true;
        }
        return false;
    }

    public function addGroupMember($member_id, $leader_id, $fetch = false): bool
    {
        $this->database->setSql('INSERT INTO `groups` (user_id, leader_id) VALUES ('.$member_id.', '.$leader_id.')');
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result) {
            if ($fetch) {
                $this->fetchGroups();
                $this->fetchGroups();
            }
            return true;
        }
        return false;
    }
}