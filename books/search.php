<?php
require_once '../entities/Book.php';

header("Content-Type: application/json; charset=UTF-8");
$searchText = filter_input(INPUT_GET, 'text', FILTER_SANITIZE_STRING);

$book = new Book();
$result = $book->search($searchText);

$response['body'] = $result;
echo json_encode($response);
