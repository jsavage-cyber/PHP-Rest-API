<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$request_method = $_SERVER['REQUEST_METHOD'];

$about = [
    "university" => "Northumbria University",
    "student_id" => "ID"
];

switch ($request_method) {
    case 'GET':
        echo json_encode($about);
        break;
default:
        http_response_code(405);
        echo json_encode("Request Method Not Allowed");
        break;
}


