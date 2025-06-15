<?php
require_once("../configDB.php");
class Conexion
{
    private $host;
    private $db;
    private $user;
    private $pass;
    private $dsn;
    private $port;
    protected $conexion;

    public function __construct()
    {
        global $hostConf, $dbConf, $userConf, $passConf, $portConf, $dsnConf;
        $this->host = $hostConf;
        $this->db = $dbConf;
        $this->user = $userConf;
        $this->pass = $passConf;
        $this->port = $portConf;
        $this->dsn = $dsnConf;
        $this->conexion = $this->crearConexion();

    }

    public function crearConexion()
    {

        try {
            $conexion = new PDO($this->dsn, $this->user, $this->pass);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            die("Error en la conexión: mensaje: " . $ex->getMessage());
        }

        return $conexion;
    }

    public function query($sql) {
        try {
            return $this->conexion->query($sql); // Return query result
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }

    public function prepare($sql) {
        return $this->conexion->prepare($sql); // Return prepared statement
    }
}
