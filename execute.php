<?php
function execute($sql = "", $param = []) {

    require_once __DIR__."/database_connection.php";
    
    try {
        $conn = database_connection();
        $result = $conn->prepare($sql);
        $result->execute($param); 
        $data = $result->fetchAll(PDO::FETCH_ASSOC);
        return $data;
 
    } catch( PDOException $e ) {
        $error = "SQL Error: " . $e->getMessage();
        echo json_encode($error);
        exit();
    }
}