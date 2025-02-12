<?php

class UserManager
{
    protected DatabaseService $database;

    function __construct($database)
    {
        $this->database = $database;
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
     * Retrieves users password (hashed)
     *
     * @param $user_id
     * @return false|mixed
     * @throws Exception
     */
    public function getUsersPassword($user_id)
    {
        $this->database->setSql("SELECT password FROM users WHERE id = '$user_id'");
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row['password'];
        }
        return false;
    }

    /**
     * Verifies if the given passwords matches the stored users password
     *
     * @param $user_id
     * @param $password
     * @return bool
     * @throws Exception
     */
    public function verifyUsersPassword($user_id, $password){
        $this->database->setSql("SELECT password FROM users WHERE id = '$user_id'");
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return password_verify($password, $row['password']);
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
    protected function setUsersPosition($user_id, $position)
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
     * @return false|mixed
     * @throws Exception
     */
    protected function getUsersPosition($user_id)
    {
        $this->database->setSql("SELECT position_id FROM users WHERE id = '$user_id'");
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row['position_id'];
        }
        return false;
    }

    /**
     * Sets users position_id
     *
     * @param $user_id
     * @param $verification
     * @return bool
     * @throws Exception
     */
    public function setUsersVerification($user_id, $verification)
    {
        $this->database->setSql("UPDATE users SET verified = '$verification' WHERE id = '$user_id'");
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
     * @return false|mixed
     * @throws Exception
     */
    public function getUsersVerification($user_id)
    {
        $this->database->setSql("SELECT verified FROM users WHERE id = '$user_id'");
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row['verified'];
        }
        return false;
    }
}