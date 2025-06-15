<?php
require_once("../configDB.php");
header('Content-Type: application/json'); // Informamos que la cabecera de la respuesta es de typo JSON
global $userConf;
global $passConf;
global $dsnConf;
try {

    $fechaReparto = isset($_POST['fechaReparto']) ? $_POST['fechaReparto'] : null;

    // Conexión a la base de datos
    $pdo = new PDO($dsnConf, $userConf, $passConf);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (empty($fechaReparto)) {
        echo json_encode(['success' => false, 'error' => 'Indique una fecha.']);
        exit;
    }

    // Recogida y validación de datos enviados vía POST
    // Se convierten a enteros para asegurar la integridad de datos
    $fechaReparto = date("Y-m-d", strtotime($_POST['fechaReparto']));

    // Verificamos que se hayan enviado todos los datos necesarios


    //Comprobamos que no haya una fecha de reparto ya registrada
    $selectDatesQuery = "SELECT * 
                  FROM repartos 
                  WHERE fecha = :fechaReparto";
    $stmtPrevDate = $pdo->prepare($selectDatesQuery);
    $stmtPrevDate->bindParam(':fechaReparto', $fechaReparto, PDO::PARAM_STR);
    $stmtPrevDate->execute();
    $reparto = $stmtPrevDate->fetch(PDO::FETCH_ASSOC);

    // Si existe previamente una fecha devolvemos un error
    if ($reparto !== false) {
        echo json_encode(['success' => false, 'error' => 'Ya existe un reparto con esa fecha.']);
        exit;
    }

    // Registramos un nuevo reparto
    $query = "INSERT INTO repartos (fecha)
              VALUES (:fechaReparto)";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':fechaReparto', $fechaReparto, PDO::PARAM_STR);
    $stmt->execute();


    //Recogemos los repartos actualizados
    $sql = "SELECT *
            FROM repartos 
            WHERE fecha = :fechaReparto";

    $stmtVal = $pdo->prepare($sql);
    $stmtVal->execute([':fechaReparto' => $fechaReparto]);
    $reparto = $stmtVal->fetch(PDO::FETCH_ASSOC);

    // Si ha ido bien, devuelve un JSON indicando éxito
    $data = [
        'success'      => true,
        'message'      => '¡Nuevo reparto añadido correctamente!',
        'repartos'    => $reparto
    ];
    echo json_encode($data); //devolvemos como JSON echo json_encode(data) los datos
    exit;

} catch (PDOException $ex) {
    // En caso de error en la base de datos se informa al cliente
    echo json_encode(['success' => false, 'error' => 'Error en la base de datos: ' . $ex->getMessage()]);
    exit;
}
?>