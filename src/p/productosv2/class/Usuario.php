<?php


class Usuario extends Conexion
{
    private $usuario;
    private $pass1;

    public function __construct()
    {
        parent::__construct();
    }
    public function isValido($u, $p)
    {
        $pass1 = hash('sha256', $p);
        $consulta = "select * from usuarios where nombre=:u AND contrasena=:p";
        $stmt = $this->conexion->prepare($consulta);
        try {
            $stmt->execute([
                ':u' => $u,
                ':p' => $pass1
            ]);
        } catch (PDOException $ex) {
            die("Error al consultar usuario: " . $ex->getMessage());
        }
        if ($stmt->rowCount() == 0) return false;
        return true;
    }

    public function getUserId($u)
    {
        // Consulta SQL parametrizada
        $consulta = "SELECT id FROM usuarios WHERE nombre = :u LIMIT 1";
        $stmt = $this->conexion->prepare($consulta); // Prepara la consulta

        try {
            // Ejecuta la consulta con los parámetros
            $stmt->execute([':u' => $u]);

            // Recupera el resultado
            $response = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($response) {
                return $response["id"]; // Devuelve el ID
            } else {
                return null;
            }
        } catch (PDOException $ex) {
            die("Error al consultar usuario: " . $ex->getMessage());
        }
    }

    public function registerUser($u, $p)
    {
        $pass1 = hash('sha256', $p);
        $consulta = "INSERT INTO usuarios (nombre, contrasena) VALUES (:u, :p)";
        $stmt = $this->conexion->prepare($consulta);
        try {
            $stmt->execute([
                ':u' => $u,
                ':p' => $pass1
            ]);
        } catch (PDOException $ex) {
            die("Error al consultar usuario: " . $ex->getMessage());
        }
        if ($stmt->rowCount() == 0) return false;
        return true;
    }
}


