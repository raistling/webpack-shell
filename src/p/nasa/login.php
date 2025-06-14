<?php
require_once "utils/sessions.php";
require_once "utils/connect_db.php";
require_once "src/views/errorUserOrPass.php";

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

    $user_Input = $_POST['user'];
    $password = $_POST['password'];

    $userDB = new UserDB();
    $isRegistered = $userDB->isUserRegistered($user_Input, $password);

    if ($isRegistered) {
        $user = $userDB->getUser($user_Input);

        startSession($user['username'], $user['token']);
        header('Location: index.php');
        exit();

    } else {
        $customMessage = errorUserOrPass();
    }
}
?>

<!doctype html>
<html lang="es" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Manuel Malvar">
    <title>NASA - INDEX</title>

    <link href="src/styles/bootstrap.min.css" rel="stylesheet">
    <link href="src/styles/styles.css" rel="stylesheet">
    <link href="src/styles/firefly.css" rel="stylesheet">
</head>
<body class="login bg-img">
    <main class="form-signin w-100 m-auto">
        <form class="card" method="POST" action="login.php">
            <figure class="card-header">
                <img class="logo w-100 logo mb-0"
                     src="src/img/nasa-logo.png"
                     alt="NASA Logo"
                     style="view-transition-name: logo-Nasa"
                />
            </figure>
            <div class="card-body">
                <div class="wrapInput mb-3">
                    <div class="form-floating">
                        <input name="user" type="text" class="form-control" id="user" placeholder="Usuario">
                        <label for="user">Usuario</label>
                    </div>
                    <div class="form-floating">
                        <input name="password" type="password" class="form-control" id="password" placeholder="Contraseña">
                        <label for="password">Contraseña</label>
                    </div>
                </div>
                <div id="errorWrap">
                    <?php if (!empty($customMessage)){
                            echo $customMessage;
                        }
                    ?>
                </div>
                <button class="btn btn-danger w-100 py-3" type="submit">Acceder</button>
                <p class="mt-2 mb-3 text-body-secondary text-end"> ¿No tienes una cuenta?
                    <a class="w-100 py-2 link-danger" href="register.php" title="Registrar una cuenta">Crear cuenta</a>
                </p>
            </div>
        </form>
    </main>

    <div class="firefly"></div>
    <div class="firefly"></div>
    <div class="firefly"></div>
    <div class="firefly"></div>
    <div class="firefly"></div>
    <div class="firefly"></div>
    <div class="firefly"></div>
    <div class="firefly"></div>
    <div class="firefly"></div>
    <div class="firefly"></div>
    <div class="firefly"></div>
    <div class="firefly"></div>
    <div class="firefly"></div>
    <div class="firefly"></div>
    <div class="firefly"></div>
    <script src="src/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
