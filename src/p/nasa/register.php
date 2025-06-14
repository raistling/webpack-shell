<?php
require_once "utils/sessions.php";
require "utils/connect_db.php";
require_once "src/views/errorUserOrPass.php";

$customMessage = '';

if($_SERVER['REQUEST_METHOD'] == 'POST' ) {

    $user_input = isset($_POST['user'])
        ? $_POST['user']
        : '';

    $password = isset($_POST['password'])
        ? $_POST['password']
        : '';

    $repeatPassword = isset($_POST['repeatPassword'])
        ? $_POST['repeatPassword']
        : '';

    $token = isset($_POST['token'])
        ? $_POST['token']
        : '';

    $connect = new UserDB();
    $isUserRegistered = $connect->getUser($user_input);

    if(empty($user_input)){
        $customMessage = userNotExist();
    }
    elseif($isUserRegistered){
        $customMessage = userMustNotExist();
    }
    elseif(empty($password)){
        $customMessage = passwordNotExist();
    }
    elseif(empty($token)){
        $customMessage = tokenNotExist();
    }
    elseif($password != $repeatPassword){
        $customMessage = errorNotEqualPass();
    }
    else {
       $query = $connect->setUser($user_input, $password, $token);
       if($query == 1){
           $customMessage = userCreated();
           startSession($user_input, $token);

           $_SESSION['username'] = $user_input;
           $_SESSION['token'] = $token;

           header('Location: index.php');
           exit();
       }else{
           $customMessage = errorCreatingUser();
       }
    }

    $connect->closeConnection();

}

?>
<!doctype html>
<html lang="es" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Registro a la app de la NASA">
    <meta name="author" content="Manuel Malvar">
    <title>NASA - REGISTER</title>
    <link href="src/styles/bootstrap.min.css" rel="stylesheet">
    <link href="src/styles/styles.css" rel="stylesheet">
    <link href="src/styles/firefly.css" rel="stylesheet">
</head>
<body class="login bg-img">
    <main class="form-signin w-100 m-auto">
        <form class="card" action="register.php" method="post">
            <figure class="card-header">
                <img class="logo w-100 logo mb-0"
                     src="src/img/nasa-logo.png"
                     alt="NASA Logo"
                     style="view-transition-name: logo-Nasa"
                     />
            </figure>
            <div class="card-body">
                <div class="wrapInput mb-3">
                    <h1 class="h4 mx-1 text-center fw-normal">¿Desea crear una cuenta?</h1>
                    <div class="form-floating">
                        <input name="user" type="text" class="form-control" id="floatingInput" placeholder="Usuario">
                        <label for="floatingInput">Usuario</label>
                    </div>
                    <div class="form-floating">
                        <input name="password" type="password" class="form-control" id="password" placeholder="Contraseña">
                        <label for="password">Contraseña</label>
                    </div>
                    <div class="form-floating">
                        <input name="repeatPassword" type="password" class="form-control" id="repeatPassword" placeholder="Repita Contraseña">
                        <label for="repeatPassword">Repita contraseña</label>
                    </div>
                    <div class="form-floating">
                        <input name="token" type="text" class="form-control" id="token" placeholder="token">
                        <label for="token">Introduzca su Token API</label>
                    </div>
                </div>
                <div id="errorWrap">
                    <?php if (!empty($customMessage)): ?>
                        <?php echo $customMessage; ?>
                    <?php endif; ?>
                </div>
                <button class="btn btn-danger w-100 py-3" type="submit">Acceder</button>
                <p class="mt-2 mb-3 text-body-secondary text-end"> ¿Ya tiene una cuenta?
                    <a class="w-100 py-2 link-danger" href="login.php" title="Registrar una cuenta">Iniciar sesión</a>
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
