<?php
require_once 'Base.php';

class Book extends Base
{

    // table columns
    public $name;
    public $year;
    public $author;
    public $publisher;
    public $no_of_pages;
    public $description;

    public function __construct()
    {
        parent::__construct('books');
    }
    
    public function search($search) {
        if (empty($search)) {
            return [];
        }
        $query = "SELECT id, name, author, category, cover FROM books WHERE ";
        $query .= "(name LIKE '%{$search}%') OR (author LIKE '%{$search}%') ";
        $query .= "OR (category LIKE '%{$search}%') OR (description LIKE '%{$search}%')";
        $result = $this->executeQuery($query);
        return $result;
    }

    protected function save()
    {
        return [];
    }
}
