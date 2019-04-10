<?php
require_once 'Base.php';

class Book extends Base
{

    // table name
    private $table_name = "books";
    // table columns
    public $id;
    public $name;
    public $year;
    public $author;
    public $publisher;
    public $no_of_pages;
    public $description;

    //C
    public function create()
    {
        return true;
    }

    /**
     * Returns prepared query statement object
     * @param string $id
     * @return PDOStatement
     */
    public function read($id = NULL)
    {
        $query = '';
        if (empty($id)) {
            $query = "SELECT * FROM {$this->table_name}";
        } else {
            $query = "SELECT * FROM {$this->table_name} WHERE id = '{$id}'";
        }
        $stmt = $this->getDBConnection()->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    //U
    public function update($id)
    {
        if (empty($id)) {
            return false;
        }
        return true;
    }

    //D
    public function delete($id)
    {
        if (empty($id)) {
            return false;
        }
        return true;
    }
}
