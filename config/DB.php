<?php

class DB
{

    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "bookflix0";
    private static $connection = NULL;

    // get the database connection
    public function getConnection()
    {
        if (static::$connection !== NULL) {
            return static::$connection;
        }

        try {
            static::$connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database, $this->username, $this->password);
            static::$connection->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Error: " . $exception->getMessage();
        }

        return static::$connection;
    }
}
