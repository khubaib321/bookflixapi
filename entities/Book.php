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

    protected function save()
    {
        return [];
    }
}
