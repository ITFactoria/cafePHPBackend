<?php
class Activo
{
    //Connnection instance
    private $connection;

    //Table name
    private $tableName = "Activos";

    //Object properties
    public $id;
    public $idRol;
    public $idUsuario;
    public $nombre;
    public $fichaTecnica;
    public $ubicacion;
    

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

    public function readbyRolUser()
    {
        
        //$query = "SELECT * from $this->tableName WHERE idRol = $this->idRol and idUsuario = $this->idUsuario";
        $query = "SELECT *  FROM " . $this->tableName . " p WHERE p.idRol =? AND p.idUsuario =?";

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $this->idRol);
        $stmt->bindParam(2, $this->idUsuario);
        
        $stmt->execute();
        return $stmt;
    }

    public function read()
    {
        $query = "SELECT p.id, p.idRol, p.idUsuario, p.nombre, p.fichaTecnica, p.ubicacion  FROM " . $this->tableName . " p WHERE p.id =?";

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        if ($row != null) {
            $this->id = $row['id'];
            $this->idRol = $row['idRol'];
            $this->idUsuario = $row['idUsuario'];
            $this->nombre = $row['nombre'];
            $this->fichaTecnica = $row['fichaTecnica'];
            $this->ubicacion = $row['ubicacion'];
            
        }
        //return $stmt;
        //return $query;
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
