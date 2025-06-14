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
    $lat = isset($_POST['lat']) ? $_POST['lat'] : null;
    $long = isset($_POST['long']) ? $_POST['long'] : null;
    $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : null;

    // Conexión a la base de datos
    $pdo = new PDO($dsnConf, $userConf, $passConf);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // Registramos un nuevo Producto para un Reparto
    $query = "INSERT INTO repartos_productos (id_reparto, id_producto, lat_gps, long_gps, direccion)
              VALUES (:idReparto, :idProducto, :lat, :long, :direccion)";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idReparto', $idReparto, PDO::PARAM_STR);
    $stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_STR);
    $stmt->bindParam(':lat', $lat, PDO::PARAM_STR);
    $stmt->bindParam(':long', $long, PDO::PARAM_STR);
    $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
    $stmt->execute();

    // Si ha ido bien, devuelve un JSON indicando éxito
    $data = [
        'success'      => true,
        'message'      => '¡Nuevo Producto añadido correctamente al Reparto!'
    ];
    echo json_encode($data); //devolvemos como JSON echo json_encode(data) los datos
    exit;

} catch (PDOException $ex) {
    // En caso de error en la base de datos se informa al cliente
    echo json_encode(['success' => false, 'error' => 'Error en la base de datos: ' . $ex->getMessage()]);
    exit;
}
?>