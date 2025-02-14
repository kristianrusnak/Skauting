<?php

class UserManager
{
    protected DatabaseService $database;

    function __construct($database)
    {
        $this->database = $database;
    }

    public function getAllLeaderUsers(): array
    {
        $this->database->setSql('
                SELECT
                    id AS user_id,
                    name,
                    email,
                    image,
                    position_id
                From users
                WHERE position_id = 4
                ORDER BY name
        ');
        $this->database->execute();
        $result = $this->database->getResult();
        $leaders = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $leaders[] = $row;
            }
        }
        return $leaders;
    }

    public function getAllUncategorizedUsers(): array
    {
        $this->database->setSql('
                SELECT 
                    u.id AS user_id,
                    u.name AS name,
                    u.email AS email,
                    u.image AS image,
                    u.position_id AS position_id
                FROM
                    (
                        (
                            SELECT 
                                id AS user_id
                            FROM users
                        )
                        EXCEPT
                        (
                        SELECT 
                            user_id
                        FROM `groups`
                        GROUP BY user_id
                        )
                    ) AS unc
                INNER JOIN users AS u ON u.id = unc.user_id
                WHERE u.position_id < 4
                ORDER BY u.name
        ');
        $this->database->execute();
        $result = $this->database->getResult();
        $users = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }

    /**
     * Adds new user to the database
     *
     * @param $name
     * @param $email
     * @param $password
     * @param $position_id
     * @param $verified
     * @return bool
     * @return void
     */
    public function addUser($name, $email, $password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $this->database->setSql("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");
        $this->database->execute();
        if ($this->database->getResult()) {
            return true;
        }
        return false;
    }

    /**
     * Verifies if the provided login credentials are correct
     *
     * @param $email
     * @param $password
     * @return array|false
     * @throws Exception
     */
    public function verifyUser($email, $password): array|false
    {
        $this->database->setSql("SELECT * FROM users WHERE email = '$email'");
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                return $row;
            }
        }
        return false;
    }

    /**
     * Deletes user from database
     *
     * @param $user_id
     * @return bool
     * @throws Exception
     */
    public function deleteUser($user_id)
    {
        $this->database->setSql("DELETE FROM users WHERE id = '$user_id'");
        $this->database->execute();
        if ($this->database->getResult()) {
            return true;
        }
        return false;
    }

    /**
     * Sets users name
     *
     * @param $user_id
     * @param $name
     * @return bool
     * @throws Exception
     */
    public function setUsersName($user_id, $name)
    {
        $this->database->setSql("UPDATE users SET name = '$name' WHERE id = '$user_id'");
        $this->database->execute();
        if ($this->database->getResult()) {
            return true;
        }
        return false;
    }

    /**
     * Retrieves users name
     *
     * @param $user_id
     * @return false|mixed
     * @throws Exception
     */
    public function getUsersName($user_id)
    {
        $this->database->setSql("SELECT name FROM users WHERE id = '$user_id'");
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row['name'];
        }
        return false;
    }

    /**
     * Sets users email
     *
     * @param $user_id
     * @param $email
     * @return bool
     * @throws Exception
     */
    public function setUsersEmail($user_id, $email)
    {
        $this->database->setSql("UPDATE users SET email = '$email' WHERE id = '$user_id'");
        $this->database->execute();
        if ($this->database->getResult()) {
            return true;
        }
        return false;
    }

    /**
     * Retrieves users email
     *
     * @param $user_id
     * @return false|mixed
     * @throws Exception
     */
    public function getUsersEmail($user_id)
    {
        $this->database->setSql("SELECT email FROM users WHERE id = '$user_id'");
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row['email'];
        }
        return false;
    }

    /**
     * Sets users password (hashed)
     *
     * @param $user_id
     * @param $password
     * @return bool
     * @throws Exception
     */
    public function setUsersPassword($user_id, $password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $this->database->setSql("UPDATE users SET password = '$password' WHERE id = '$user_id'");
        $this->database->execute();
        if ($this->database->getResult()) {
            return true;
        }
        return false;
    }

    /**
     * Sets users position_id
     *
     * @param $user_id
     * @param $position
     * @return bool
     * @throws Exception
     */
    public function setUsersPosition($user_id, $position): bool
    {
        $this->database->setSql("UPDATE users SET position_id = '$position' WHERE id = '$user_id'");
        $this->database->execute();
        if ($this->database->getResult()) {
            return true;
        }
        return false;
    }

    /**
     * Retrieves users position_id
     *
     * @param $user_id
     * @return int
     * @throws Exception
     */
    public function getUserPosition($user_id): int
    {
        $this->database->setSql("SELECT position_id FROM users WHERE id = '$user_id'");
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row['position_id'];
        }
        return -1;
    }
}