<?php
require_once("../configDB.php");
header('Content-Type: application/json'); // Informamos que la respuesta es JSON
global $userConf;
global $passConf;
global $dsnConf;
try {

    // Conexión a la base de datos
    $pdo = new PDO($dsnConf, $userConf, $passConf);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recogida y validación de datos enviados vía POST
    // Se convierten a enteros para asegurar la integridad de datos
    $idProducto = isset($_POST['id_producto']) ? (int) $_POST['id_producto'] : 0;
    $idUsuario  = isset($_POST['userId']) ? (int) $_POST['userId'] : 0;
    $valoracion = isset($_POST['valoracion']) ? (int) $_POST['valoracion'] : 0;

    // Verificamos que se hayan enviado todos los datos necesarios
    if (!$idProducto || !$idUsuario || !$valoracion) {
        echo json_encode(['success' => false, 'error' => 'Faltan datos necesarios.']);
        exit;
    }

    // Validamos que la valoración esté en el rango permitido (1 a 5)
    if ($valoracion < 1 || $valoracion > 5) {
        echo json_encode(['success' => false, 'error' => 'Valoración no válida.']);
        exit;
    }

    //Comprobamos que no haya registro del id usuario en la valoracion del producto
    $userRatedQuery = "SELECT * 
                  FROM valoraciones 
                  WHERE id_producto = :id_producto AND id_usuario = :id_usuario";
    $stmtUser = $pdo->prepare($userRatedQuery);
    $stmtUser->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
    $stmtUser->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
    $stmtUser->execute();
    $userRated = $stmtUser->fetch(PDO::FETCH_ASSOC);

    // Si existe ya una valoración de ese usuario devolvemos un error
    if ($userRated !== false) {
        echo json_encode(['success' => false, 'error' => 'Ya has votado por este producto.']);
        exit;
    }

    // Registramos una nueva valoración.
    $query = "INSERT INTO valoraciones (id_usuario, id_producto, valoracion)
              VALUES (:id_usuario, :id_producto, :valoracion)
              ON DUPLICATE KEY UPDATE valoracion = VALUES(valoracion)";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
    $stmt->bindParam(':valoracion', $valoracion, PDO::PARAM_INT);
    $stmt->execute();


    //Recogemos los nuevos datos
    $sql = "SELECT COUNT(*) as total_votos, AVG(valoracion) as valoracion 
            FROM valoraciones 
            WHERE id_producto = :id";

    $stmtVal = $pdo->prepare($sql);
    $stmtVal->execute([':id' => $idProducto]);
    $val = $stmtVal->fetch(PDO::FETCH_ASSOC);
    $nuevaValoracion = $val["valoracion"];  // Ejemplo: valoración promedio actualizada
    $nuevoTotalVotos = $val["total_votos"]; // Ejemplo: total de votos actualizado

    // Si todo ha ido bien se devuelve un JSON indicando éxito
    $data = [
        'success'      => true,
        'message'      => '¡Gracias por tu valoración!',
        'newRating'    => $nuevaValoracion,
        'numVotes'     => $nuevoTotalVotos
    ];
    echo json_encode($data);
    exit;

} catch (PDOException $ex) {
    // En caso de error en la base de datos se informa al cliente
    echo json_encode(['success' => false, 'error' => 'Error en la base de datos: ' . $ex->getMessage()]);
    exit;
}
?>