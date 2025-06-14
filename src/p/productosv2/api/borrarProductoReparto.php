<?php
require_once("../configDB.php");
header('Content-Type: application/json'); // Informamos que la cabecera de la respuesta es de typo JSON
global $userConf;
global $passConf;
global $dsnConf;
try {

    // Recogida y validación de datos enviados vía POST
    // Verificamos que se hayan enviado todos los datos necesarios
    $idReparto = isset($_POST['repartoId']) ? $_POST['repartoId'] : null;
    $idProducto = isset($_POST['productoId']) ? $_POST['productoId'] : null;

    // Conexión a la base de datos
    $pdo = new PDO($dsnConf, $userConf, $passConf);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // Recogida y validación de datos enviados vía POST
    if (empty($idReparto) || empty($idProducto)) {
        echo json_encode(['success' => false, 'error' => 'Error al seleccionar el reparto.']);
        exit;
    }


    //Comprobamos que no haya registro del id usuario en la valoracion del producto
    $selectQuery = "DELETE FROM repartos_productos 
                    WHERE id_reparto = :idReparto 
                      AND id_producto = :idProducto";
    $stmtReparto = $pdo->prepare($selectQuery);
    $stmtReparto->bindParam(':idReparto', $idReparto, PDO::PARAM_STR);
    $stmtReparto->bindParam(':idProducto', $idProducto, PDO::PARAM_STR);
    $stmtReparto->execute();

    $deletedRows = $stmtReparto->rowCount(); // Contamos las filas eliminadas.

    if ($deletedRows == 0) {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar el producto del reparto en la BBDD.']);
        exit;
    }

    $data = [
        'success'      => true,
        'message'      => 'Producto eliminado del reparto correctamente!',
    ];
    echo json_encode($data);
    exit;

} catch (PDOException $ex) {
    // En caso de error en la base de datos se informa al cliente
    echo json_encode(['success' => false, 'error' => 'Error en la base de datos: ' . $ex->getMessage()]);
    exit;
}
?>