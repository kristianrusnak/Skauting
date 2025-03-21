<?php

class DatabaseService extends MysqliService
{
    /**
     * Sql text fro mysqli
     *
     * @var string
     */
    private string $sql = "";

    /**
     * Response from mysqli
     *
     * @var bool|object
     */
    private bool|object $result;

    /**
     * Sets the sql text
     *
     * @param $sql
     * @return void
     */
    public function setSql($sql): void
    {
        $this->sql = $sql;
    }

    /**
     * Returns the sql text
     *
     * @return string
     */
    public function getSql(): string
    {
        return $this->sql;
    }

    /**
     * Executes the mysqli query and stores the result in $result
     *
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $this->result = $this->query($this->sql);
    }

    /**
     * Returns the $result
     *
     * @return bool|object
     */
    public function getResult(): bool|object
    {
        if (isset($this->result)){
            return $this->result;
        }
        return false;
    }

    /**
     * Retrieves auto increment from mysqli
     *
     * @return integer
     */
    public function getAutoIncrement(): int
    {
        return $this->returnAutoIncrement();
    }
}
?>