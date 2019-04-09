<?php
include_once '../config/DB.php';

class Base
{

    private $db = NULL;
    private static $dbConnection = NULL;

    public function __construct()
    {
        $this->db = new DB();
        Base::$dbConnection = $this->db->getConnection();
    }

    /**
     * Returns database connection object
     * @return PDO
     */
    public function getDBConnection()
    {
        if (Base::$dbConnection === NULL) {
            Base::$dbConnection = $this->db->getConnection();
        }
        return Base::$dbConnection;
    }
}
