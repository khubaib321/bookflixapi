<?php
require_once '../entities/Book.php';

header("Content-Type: application/json; charset=UTF-8");
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

$book = new Book();
$stmt = $book->read($id);
$count = $stmt->rowCount();

if ($count > 0) {
    $books = array();
    $books["body"] = array();
    $books["count"] = $count;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        array_push($books["body"], $row);
    }

    echo json_encode($books);
} else {
    echo json_encode(
        array("body" => array(), "count" => 0)
    );
}
