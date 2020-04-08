<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

//include database and object files
include_once '../config/database.php';
include_once '../models/qr.php';

$database = new Database();
$connection = $database->getConnection();

//inialize object
$qr = new Qr($connection);

// set ID property of record to read
$qr->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of user to be edited
$qr->read();

if ($qr->clave != null) {
    // create array
    $qr_array = array(
        "id" =>  $qr->id,
        "clave" => $qr->clave,
        "fechacreacion" => $qr->fechacreacion,
        "fechacompra" => $qr->fechacompra,
        "fechaactivacion" => $qr->fechaactivacion,
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode($qr_array);
} else {

    
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user product does not exist
    echo json_encode(array("message" => "Object does not exist."));
}
