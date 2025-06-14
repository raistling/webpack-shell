<?php 

class RepartoProductos {
    private $pdo; // Conexión a la base de datos

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método para obtener todos los productos de un Reparto
    public function obtenerProductos($idReparto) {
        $sql = "SELECT 
                    p.id AS id_producto,
                    p.nombre AS nombre_producto,
                    rp.lat_gps,
                    rp.long_gps,
                    rp.direccion,
                    r.fecha AS fecha_reparto,
                    r.id AS id_reparto
                FROM repartos_productos rp
                JOIN productos p ON rp.id_producto = p.id
                JOIN repartos r ON rp.id_reparto = r.id
                WHERE r.id = :idReparto";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idReparto' => $idReparto]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>