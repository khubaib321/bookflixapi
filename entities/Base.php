<?php
include_once '../config/DB.php';

abstract class Base
{

    public $id = '';
    private $db = NULL;
    private $tableName = '';
    private static $dbConnection = NULL;

    public function __construct($tableName)
    {
        $this->db = new DB();
        $this->tableName = $tableName;
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

    // C
    public function create($params, $save = false)
    {
        return true;
    }

    /**
     * R
     * Returns prepared query statement object
     * @param string $read_all
     * @param string $id
     * @return PDOStatement
     */
    public function read($read_all = false, $id = NULL)
    {
        $query = '';
        if (empty($id) && $read_all) {
            $query = "SELECT * FROM {$this->tableName}";
        } else {
            $query = "SELECT * FROM {$this->tableName} WHERE id = '{$id}'";
        }
        $stmt = $this->getDBConnection()->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // U
    public function update($id)
    {
        if (empty($id)) {
            return false;
        }
        return true;
    }

    // D
    public function delete($id)
    {
        if (empty($id)) {
            return false;
        }
        return true;
    }

    /**
     * Returns table result in PDOStatement as array
     * @param PDOStatement $statement
     * @return array
     */
    public function getResultRows(&$statement)
    {
        $result = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $result[] = $row;
        }
        return $result;
    }

    /**
     * Executes query and returns result in rows
     * @param string $query
     * @return type
     */
    public function executeQuery($query)
    {
        $statement = $this->getDBConnection()->prepare($query);
        $statement->execute();
        return $this->getResultRows($statement);
    }

    /**
     * Checks where record exists in db or not
     * @param string $table table name
     * @param array $fields key value pairs; AND operator applied
     * @param string $orderBy
     * @param boolean $returnRecord
     * @return boolean
     */
    public function recordExists($table, $fields, $orderBy = '', $returnRecord = false)
    {
        $query = "SELECT * FROM {$table} ";
        $queryWhere = '';
        foreach ($fields as $col => $value) {
            if (empty($queryWhere)) {
                $queryWhere .= "WHERE {$col} = '{$value}' ";
            } else {
                $queryWhere .= "AND {$col} = '{$value}' ";
            }
        }
        $query .= $queryWhere;
        if (!empty($orderBy)) {
            $query .= "ORDER BY {$orderBy}";
        }
        $result = $this->executeQuery($query);
        if ($returnRecord && !empty($result)) {
            return $result[0];
        }
        return !empty($result);
    }

    protected abstract function save();
}
