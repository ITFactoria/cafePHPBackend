<?php
class Database
{
    //Local environment
    /*private $host = "localhost";
    private $dbname = "cafe";
    private $user = "root";
    private $pswd = "";
    public $connection;*/

    //Dev environment
    private $host = "localhost";
    private $dbname = "cafe";
    private $user = "root";
    private $pswd = "Admin2020";
    public $connection;


    public function getConnection()
    {
        $this->connection = null;
        try {
            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->user, $this->pswd);
            $this->connection->exec("set names utf8");
        } catch (PDOException $pdoException) {
            echo "Error: " . $pdoException->getMessage();
        }
        return $this->connection;
    }
}
