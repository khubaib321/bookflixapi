<?php
require_once '../entities/User.php';

header("Content-Type: application/json; charset=UTF-8");
$name = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_STRING);
$phone1 = filter_input(INPUT_GET, 'phone1', FILTER_SANITIZE_STRING);
$phone2 = filter_input(INPUT_GET, 'phone2', FILTER_SANITIZE_STRING);

$user = new User();
$result = $user->create([
    'name' => $name,
    'email' => $email,
    'phone1' => $phone1,
    'phone2' => $phone2,
    ], TRUE
);

$response['body'] = array();
array_push($response['body'], $result);
echo json_encode($response);
