<?php
include_once 'config.php';

class DbConn {

    private $dbConnection = null;

    function __construct()
    {
        try 
        {
            $this->dbConnection = new \PDO(
                "mysql:host=$host;charset=utf8mb4;dbname=$database",
                $username,
                $password
            );

        } catch (\PDOException $e) 
        {
            exit($e->getMessage());
        }
    }

    function getConnection()
    {
        return $this->dbConnection;
    }
}

?>