<?php

class DB
{

    private $ENV = 0;
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "bookflix0";
    private static $connection = NULL;

    public function __construct()
    {
        if ($this->ENV === 1) {
            require 'credentails/prod.php';
        } else {
            require 'credentails/local.php';
        }
        // load credentials according to environment
        if (isset($dbCredentials) &&
            !empty($dbCredentials) &&
            is_array($dbCredentials)
        ) {
            $this->host = $dbCredentials['host'];
            $this->username = $dbCredentials['username'];
            $this->password = $dbCredentials['password'];
            $this->database = $dbCredentials['database'];
        }
    }

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
