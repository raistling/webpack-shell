<?php 

class Reparto {
    private $pdo; // Conexión a la base de datos

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método para obtener todos los productos
    public function obtenerPedidos() {
        $sql = "SELECT * FROM repartos ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);

        if ($stmt->rowCount() == 0) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPedido($fecha) {
        $sql = "SELECT * FROM repartos WHERE fecha = :fecha";
        $stmt = $this->pdo->query($sql);
        $stmt->execute([':fecha' => $fecha]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>