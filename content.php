<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PATCH');

require_once 'database/execute.php';

$author          = $_GET['author-id'] ?? '';
$content         = $_GET['content-id'] ?? '';
$page            = $_GET['page'] ?? '';
$body_patch      = json_decode(file_get_contents('php://input'),true);
$content_id      = $body_patch['id'] ?? null;
$award_id        = $body_patch['award_id'] ?? null;
$params          = [];
$request_method  = $_SERVER['REQUEST_METHOD'];


switch($request_method) {
    case'PATCH':
        if ($content_id !== null && array_key_exists('award_id', $body_patch)) {
        $sql = "UPDATE dis2025_content 
                SET award_id = ? 
                WHERE id = ?"; 
        $params = [$award_id, $content_id];
        $data = execute($sql, $params);
        echo json_encode($data);
        exit();
        }
        else {
        http_response_code(400);
        echo json_encode("Error body data missing/incorrect");
        exit();}
    case'GET':
    if (!empty($content)) {
    $sql = "SELECT id AS content_id, award_id, title, abstract, type, track  FROM dis2025_content WHERE id = ?";
    $params = [$content];
    }  
    elseif (!empty($author)) {
        $sql = "SELECT dis2025_content_author.author_id AS author_id
        , dis2025_content.id, dis2025_content.title, dis2025_content.abstract, 
        dis2025_content.type
        FROM dis2025_content
        JOIN dis2025_content_author ON dis2025_content.id = dis2025_content_author.content_id
        WHERE dis2025_content_author.author_id = ?";
        $params = [$author];
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
            $offset = (int)($page - 1) * 10;                                // had to make the page value into an integer as PDO was reading the input as a string
            $sql = "SELECT id AS content_id
            , award_id, title, abstract, type, track 
            FROM dis2025_content
            ORDER BY dis2025_content.id
            LIMIT 10 OFFSET " . (int)$offset; 
            $params = []; }
    else {
        $sql = "SELECT dis2025_content.id AS content_id
        , dis2025_content.title, dis2025_content.abstract, 
        dis2025_content.type, dis2025_award.name AS award
        FROM dis2025_content
        LEFT JOIN dis2025_award ON dis2025_content.award_id = dis2025_award.id" ;
        $params = []; }


        $data = execute($sql, $params);
        echo json_encode($data);
        exit();

    default:
        http_response_code(405);
        echo json_encode("Request Method Not Allowed");
    break;
}


