<?php

require_once 'database/execute.php';

header("Access-Control-Allow-Headers: Authorization, Content-Type");
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
$all_headers = getallheaders();
$auth        = $all_headers['Authorization'] ?? '';

if ($auth !== 'Bearer') {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorised"]);
    exit();}
else{
    http_response_code(200); 
}


$url_path = parse_url($_SERVER["REQUEST_URI"])['path']; 

switch ($url_path) {
    case '/KV5035/CW-2/about':
        require_once 'endpoints/about.php';
        break;
    case '/KV5035/CW-2/author':
        require_once 'endpoints/author.php';
        break;
    case '/KV5035/CW-2/content':
        require_once 'endpoints/content.php';
        break;
    case '/KV5035/CW-2/award':
        require_once 'endpoints/award.php';
        break;
    default:
        http_response_code(404);
        echo json_encode("Endpoint Not Found");
        break;
}
