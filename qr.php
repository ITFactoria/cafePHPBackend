<?php
class Qr
{
    //Connnection instance
    private $connection;

    //Table name
    private $tableName = "qr";

    //Object properties
    public $id;
    public $clave;
    public $fechacreacion;
    public $fechacompra;
    public $fechaactivacion;


    //Constructor
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function list()
    {
        $query = "SELECT * from $this->tableName";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read()
    {
        $query = "SELECT p.idqr, p.clave, p.fechacreado, p.fechacompra, p.fechaactivacion FROM " . $this->tableName . " p WHERE p.idqr =?";

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        if ($row != null) {
            $this->id = $row['idqr'];
            $this->clave = $row['clave'];
            $this->fechacreacion = $row['fechacreado'];
            $this->fechacompra = $row['fechacompra'];
            $this->fechaactivacion = $row['fechaactivacion'];
        }
        //return $stmt;
    }
    
    public function update()
    {
    }
    
    public function delete()
    {
    }
    
    public function create()
    {
    }
}
