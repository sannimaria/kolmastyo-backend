<?php
// lista frontendiin
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST,OPTIONS');
header('Access-Control-Allow-Headers: Accept,Content-Type,Access-Control-Allow-Header');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    return 0;
}


$input = json_decode(file_get_contents('php://input'));
$description = filter_var($input->description,FILTER_SANITIZE_STRING);




try {
    $db = new PDO('mysql:host=localhost;dbname=shoppinglist;charset=utf8','root','');
    $db->setAttribute(PDO::ATR_ERRMODE,PDO::ERRMODE_EXCEPTION);


    $query = $db->prepare('insert into task(description) values (:description');
    $query->bindValue('::description',$description,PDO::PARAM_STR);
    $query->execute();

   
    header('HTTP/1.1 200 OK');
    $data = array('id' => $db->lastInsertId(), 'description' => $description);
    echo json_encode($data);
} 
catch(PDOException $pdoex) {
    header('HTTP/1.1 500 Internal Server Error');
    $error = array('error' => $pdoex->getMessage());
    echo json_encode($error);
}