<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once 'database/execute.php';

$author          = $_GET['author-id'] ?? '';
$content         = $_GET['content-id'] ?? '';
$page            = $_GET['page'] ?? '';
$params          = [];



if (!empty($author)) {
    $sql = "SELECT id AS author_id, name FROM dis2025_author WHERE id = ?";
    $params = [$author];
}  
elseif (!empty($content)) {
    $sql = "SELECT dis2025_content_author.content_id, dis2025_content_author.author_id, dis2025_author.name FROM dis2025_content_author 
            JOIN dis2025_author ON dis2025_content_author.author_id = dis2025_author.id WHERE content_id = ?";
    $params = [$content];
}  
elseif (!empty($page)|| $page === '0') {
            if (!is_numeric($page)) {
                http_response_code(400);
                echo json_encode("Page parameter must be an integer");
                exit(); }
            if ($page <= 0) {
                http_response_code(400);
                echo json_encode("Page parameter must be greater than 0");
                exit(); }
            $offset = (int)($page - 1) * 10;                               
            $sql = "SELECT id AS author_id
            , name 
            FROM dis2025_author
            ORDER BY dis2025_author.id
            LIMIT 10 OFFSET " . (int)$offset; 
            $params = []; }
else {
    $sql = "SELECT id AS author_id
    , name
    FROM dis2025_author";
    $params = []; 
}

$data = execute($sql, $params);
echo json_encode($data);

