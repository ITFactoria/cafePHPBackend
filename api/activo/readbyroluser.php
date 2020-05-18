<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");


//include database and object files
include_once '../config/database.php';
include_once '../models/activo.php';

$database = new Database();
$connection = $database->getConnection();

//inialize object
$activo = new Activo($connection);

// set ID property of record to read
$activo->idRol = isset($_GET['idRol']) ? $_GET['idRol'] : die();
$activo->idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : die();



//query objects
$stmt = $activo->readbyRolUser();
$count = $stmt->rowCount();

if ($count > 0) {

    $activos = array();
    $activos["activos"] = array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        //extract row
        extract($row);

        $activo_item  = array(
            "id" => $id,
            "idRol" => $idRol,
            "idUsuario" => $idUsuario,
            "nombre" => $nombre,
            "fichaTecnica" => $fichaTecnica,
            "ubicacion" => $ubicacion,
            
        );
        array_push($activos["activos"], $activo_item);
        //array_push($qr_item);
    }
    //set response code - 200: OK
    http_response_code(200);

    //show products data in json format
    echo json_encode($activos);
} else {
    //set response code - 404: Not Found
    http_response_code(404);
    echo json_encode(
        array("message" => "No activos found". $activo->idRol ." ".$activo->idUsuario)
    );
    #array("body" => array(), "count" => 0));
}
