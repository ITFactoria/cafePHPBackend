<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");


//include database and object files
include_once '../config/database.php';
include_once '../models/qr.php';

$database = new Database();
$connection = $database->getConnection();

//inialize object
$qr = new Qr($connection);

//query objects
$stmt = $qr->list();
$count = $stmt->rowCount();

if ($count > 0) {

    $qrs = array();
    $qrs["records"] = array();

    //$qrs["count"] = $count;

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        //extract row
        extract($row);

        $qr_item  = array(
            "id" => $idqr,
            "clave" => $clave,
            "fechacreacion" => $fechacreado,
            "fechacompra" => $fechacompra,
            "fechaactivacion" => $fechaactivacion,
        );
        array_push($qrs["records"], $qr_item);
        //array_push($qr_item);
    }
    //set response code - 200: OK
    http_response_code(200);

    //show products data in json format
    echo json_encode($qrs);
} else {
    //set response code - 404: Not Found
    http_response_code(404);
    echo json_encode(
        array("message" => "No users found")
    );
    #array("body" => array(), "count" => 0));
}
