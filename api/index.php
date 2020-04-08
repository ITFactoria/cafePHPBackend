<form action="" method="POST">
    <label>Enter QR Id:</label><br />
    <input type="text" name="qr_id" placeholder="Enter QR Id" required />
    <br /><br />
    <button type="submit" name="submit">Submit</button>
</form>

<?php
if (isset($_POST['qr_id']) && $_POST['qr_id'] != "") {
    $qr_id = $_POST['qr_id'];

    //$url = "http://localhost/rest/api/" . $order_id;
    //Local Environment
    //$url = "http://192.168.64.2/cafe/api/qr/read.php?id=" . $qr_id;
    
    //Dev Environment
    $url="http://149.56.130.61:8080/cafev3/api/qr/read.php?id=" . $qr_id;


    $qr = curl_init($url);
    curl_setopt($qr, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($qr);
    echo "API response: ".$response;

    $result = json_decode($response);
    
    echo "<h3>Resultado Consulta QR</h3>";
    echo "<table>";
    echo "<tr><td>QR Id:</td><td>$result->id</td></tr>";
    echo "<tr><td>Clave:</td><td>$result->clave</td></tr>";
    echo "<tr><td>Fecha creacion:</td><td>$result->fechacreacion</td></tr>";
    echo "<tr><td>Fecha compra:</td><td>$result->fechacreacion</td></tr>";
    echo "<tr><td>Fecha activacion:</td><td>$result->fechaactivacion</td></tr>";
    echo "</table>";
}
?>