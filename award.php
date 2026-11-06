<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,PATCH,PUT','DELETE');

require_once 'database/execute.php';

$request_method  = $_SERVER['REQUEST_METHOD'];
$body_patch      = json_decode(file_get_contents('php://input'),true);
$id_patch        = $body_patch['id'] ?? '';
$name_patch      = $body_patch['name'] ?? '';
$id_delete       = json_decode(file_get_contents('php://input'),true);


$author_id       = $_GET['author_id'] ?? '';
$presentation_id = $_GET['presentation_id'] ?? '';

$params = [];

switch($request_method) {
    case'GET':
            $sql = "SELECT id AS award_id
            , name
            FROM dis2025_award";
            $params = []; 
        
        $data = execute($sql, $params);
        echo json_encode($data);   
        break;
    case'POST':
        if ($body_patch === null) { 
        http_response_code(400); 
        echo json_encode("Invalid JSON in body"); 
        exit(); 
        }   
        if (array_key_exists('id', $body_patch)) {
        $award_id = $body_patch['id'];
    } else {
        http_response_code(400);
        echo json_encode("Error an id is required");
        exit();
    }    
    if (array_key_exists('name', $body_patch)) {
        $award_name = $body_patch['name'];
    } else {
        http_response_code(400);
        echo json_encode("Error a name is required");
        exit();
    }
    $sql = "INSERT INTO dis2025_award (id, name) VALUES (:id, :name)";
    $param = [
        ':id' => $award_id,
        ':name' => $award_name
    ];
    $data = execute($sql, $param);
    break;

    case 'PUT':
    case 'PATCH':
        if (!empty($id_patch) && !empty($name_patch)) {
        $sql = "UPDATE dis2025_award 
                SET name = ? 
                WHERE id = ?"; 
        $params = [$name_patch, $id_patch];
        $data = execute($sql, $params);
        echo json_encode($data);
        exit();
        }
        else {
        http_response_code(400);
        echo json_encode("Error body data missing/incorrect");
        exit();}
         
    break;
    case 'DELETE':
            if (!empty($id_delete)) {
                $award_id = $id_delete['id'];
        $sql = "DELETE FROM dis2025_award WHERE id = :id";
        $param = ['id' => $award_id];
        $data = execute($sql, $param);
        echo json_encode($data);
        exit();
        }
        else {
            http_response_code(400);
            echo json_encode("DELETE request Error");
        exit();
        }
            
        break;
    default:
        http_response_code(405);
        echo json_encode("Request Method Not Allowed");
    break;
}