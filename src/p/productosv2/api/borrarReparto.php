<?php
require_once("../configDB.php");
header('Content-Type: application/json'); // Informamos que la cabecera de la respuesta es de typo JSON
global $userConf;
global $passConf;
global $dsnConf;
try {

    $id = isset($_POST['id']) ? $_POST['id'] : null;

    // Conexión a la base de datos
    $pdo = new PDO($dsnConf, $userConf, $passConf);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // Recogida y validación de datos enviados vía POST
    if (empty($id)) {
        echo json_encode(['success' => false, 'error' => 'Error al seleccionar el reparto.']);
        exit;
    }


    //Comprobamos que no haya registro del id usuario en la valoracion del producto
    $selectQuery = "DELETE FROM repartos WHERE id = :id";
    $stmtReparto = $pdo->prepare($selectQuery);
    $stmtReparto->bindParam(':id', $id, PDO::PARAM_STR);
    $stmtReparto->execute();

    $deletedRows = $stmtReparto->rowCount(); // Contamos las filas eliminadas.

    if ($deletedRows == 0) {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar el reparto en la BBDD.']);
        exit;
    }

    $data = [
        'success'      => true,
        'message'      => 'Reparto Eliminado correctamente!',
    ];
    echo json_encode($data);
    exit;

} catch (PDOException $ex) {
    // En caso de error en la base de datos se informa al cliente
    echo json_encode(['success' => false, 'error' => 'Error en la base de datos: ' . $ex->getMessage()]);
    exit;
}
?>