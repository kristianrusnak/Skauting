<?php

abstract class MysqliService
{
    /**
     * Mysqli Connection
     *
     * @var object
     */
    private object $mysqli;

    /**
     * @return void
     * @throws Exception
     */
    function __construct()
    {
        $this->initiate();
    }

    /**
     * @return void
     */
    function __destruct()
    {
        $this->mysqli->close();
    }

    /**
     * Creates mysqli connection
     *
     * @return void
     * @throws Exception
     */
    private function initiate(): void
    {
        $this->mysqli = new mysqli('localhost', 'root', '', 'skaut', null, '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock');
        $this->isConnected();
        $this->mysqli->query("SET CHARACTER SET 'utf8'");
    }

    /**
     * Checks if the connection is valid
     *
     * @return void
     * @throws Exception
     */
    private function isConnected(): void
    {
        if ($this->mysqli->connect_errno) {
            throw new Exception("Connection failed: " . $this->mysqli->connect_error);
        }
    }

    /**
     * For selecting from mysqli (SELECT)
     *
     * @param $sql {String}
     * @return object|bool
     * @throws Exception
     */
    public function query($sql): object|bool
    {
        $this->isConnected();
        return $this->mysqli->query($sql);
    }

    /**
     * Returns auto increment
     *
     * @return integer
     */
    public function returnAutoIncrement(): int
    {
        return $this->mysqli->insert_id;
    }
}
?>