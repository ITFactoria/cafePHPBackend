<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

//include database and object files
include_once '../config/database.php';
include_once '../models/activo.php';

$database = new Database();
$connection = $database->getConnection();

//inialize object
$activo = new Activo($connection);

// set ID property of record to read
$activo->id = isset($_GET['id']) ? $_GET['id'] : die();


// read the details of user to be edited
$activo->read();

if ($activo->nombre != null) {
    // create array
    $activo_array = array(
        "id" =>  $activo->id,
        "idRol" =>  $activo->idRol,
        "idUsuario" =>  $activo->idUsuario,
        "nombre" => $activo->nombre,
        "fichaTecnica" => $activo->fichaTecnica,
        "ubicacion" => $activo->ubicacion,
       
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode($activo_array);
} else {

    
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user product does not exist
    echo json_encode(array("message" => "Object does not exist." . $result));
}
