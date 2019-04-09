<?php
require_once 'DB.php';

if (file_exists('scripts/db_ddl.sql')) {
    // file exists
    $sql = file_get_contents('db/db_ddl.sql');
    try {
        $db = new DB();
        $db->getConnection()->exec($sql);
        echo "Database and tables created successfully!";
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}