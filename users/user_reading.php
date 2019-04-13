<?php
require_once '../entities/User.php';

header("Content-Type: application/json; charset=UTF-8");
$bookID = filter_input(INPUT_GET, 'book_id', FILTER_SANITIZE_STRING);
$pageNo = filter_input(INPUT_GET, 'page_no', FILTER_SANITIZE_STRING);
$userEmail = filter_input(INPUT_GET, 'user_email', FILTER_SANITIZE_STRING);

$user = new User();
$user->create(['email' => $userEmail]);
$result = $user->saveReadingProgress([
    'book_id' => $bookID,
    'page_no' => $pageNo,
    ]
);

$response['body'] = array();
array_push($response['body'], $result);
echo json_encode($response);
