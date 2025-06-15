<?php 

class Producto {
    private $pdo; // Conexión a la base de datos

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método para obtener todos los productos
    public function obtenerProductos() {
        $sql = "SELECT * FROM productos";
        $stmt = $this->pdo->query($sql);

        if ($stmt->rowCount() == 0) {
            return [];
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    }

    public function valoracionProducto($id){
        $sql = "SELECT COUNT(*) as total_votos, AVG(valoracion) as valoracion FROM valoraciones WHERE id_producto = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>