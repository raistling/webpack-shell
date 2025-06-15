<?php
include_once 'utils/config.php';
include_once 'utils/printMessages.php';

global $NO_ESTABLECIDO;

session_start();
    $lang = isset($_SESSION['lang'])
                ? $_SESSION['lang']
                : $NO_ESTABLECIDO;

    $profile = isset($_SESSION['profile'])
                ? $_SESSION['profile']
                : $NO_ESTABLECIDO;

    $timezone = isset($_SESSION['timezone'])
                ? $_SESSION['timezone']
                : $NO_ESTABLECIDO;

    $infoMessage = '';

    if ( $_SERVER["REQUEST_METHOD"] == "POST"){

        session_destroy();

        $lang = $NO_ESTABLECIDO;
        $profile = $NO_ESTABLECIDO;
        $timezone = $NO_ESTABLECIDO;

        if(
            empty( $_SESSION['lang'] ) &&
            empty( $_SESSION['profile'] ) &&
            empty( $_SESSION['timezone']  )){
                $infoMessage = preferencesEmpty();
        } else {
            $infoMessage = preferencesDeleted();
        }

    }

?>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mostrar</title>
        <link rel="stylesheet" href="src/styles/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    </head>
    <body class="bg-light">
        <div class="container d-flex justify-content-center py-5">
            <div class="card bg-success text-light" style="width: 30rem;">
                <form method="post" action="mostrar.php" class="mb-0">
                    <div class="card-header d-flex align-items-center">
                        <i class="h4 me-2 bi bi-person-fill-gear"></i>
                        <h1 class="h4">Preferencias de usuario</h1>
                    </div>
                    <div class="card-body">
                        <ul class="list-group bg-success text-light">
                            <li class="list-group-item">
                                <i class="bi bi-translate flex me-2"></i>
                                <strong>Idioma: </strong>
                                <?php echo isset($lang)
                                    ? $lang
                                    : $NO_ESTABLECIDO;
                                ?>
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-people-fill flex me-2"></i><strong>Perfil público: </strong>
                                <?php echo isset($profile)
                                    ? $profile
                                    : $NO_ESTABLECIDO;
                                ?>
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-clock flex me-2"></i>
                                <strong>Zona horaria: </strong>
                                <?php echo isset($timezone)
                                    ? $timezone
                                    : $NO_ESTABLECIDO;
                                ?>
                            </li>
                        </ul>
                        <div>
                            <?php
                                echo !empty($infoMessage)
                                    ? $infoMessage
                                    : '';
                            ?>
                        </div>
                    </div>
                    <div class="card-footer justify-content-between d-flex align-content-between w-100 py-3">
                        <a href="preferencias.php" class="btn btn-primary">Establecer</a>
                        <button type="submit" class="btn btn-danger">Borrar</button>
                    </div>
                </form>
            </div>
        </div>


        <script src="src/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    </body>
</html>