<?php

function database_connection() {

    require_once __DIR__."/credentials.php";
 
    try {
   
        $connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
        return $connection;
 
    } catch( PDOException $e ) {
  
        $error = "Database Connection Error: " . $e->getMessage();
        echo json_encode($error);
        
        exit();
    }
}