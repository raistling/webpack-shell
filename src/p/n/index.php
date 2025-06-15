<?php
require_once "utils/config.php";
require_once "utils/sessions.php";
require_once "utils/functions.php";
require_once "src/model/nasa.php";
require_once "src/model/neo.php";
require_once "src/views/mediaType.php";

    global $TOTAL_REQUEST_PER_SESSION;
    global $INIT_REQUEST_PER_SESSION;
    global $initDate;
    global $alertSms;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
        session_destroy();
        header('Location: login.php');
        exit();
    }

    if (!isset($_SESSION['username'])) {
        session_destroy();
        header('Location: login.php');
        exit();
    }

    $user = $_SESSION['username'];
    $token = $_SESSION['token'];
    $session_request = $_SESSION['request'];

    $initialLetterUser = firstChar($user);
    $initDate = date('Y-m-d');

    $nasa = new Nasa($initDate);
    $neo = new Neo($initDate);


    $rateLimit = $nasa->getRateLimit();
    $response = $nasa->getData();
    $rateRemaining = $nasa->getRateRemaining();

    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['dateInput'])) {
        $initDate = $_GET['dateInput'];
        $nasa = new Nasa($initDate);
        $neo = new Neo($initDate);
        $response = $nasa->getData();
        $headers = $nasa->getHeaders();

        updateRequestPerSession();
        $alertSms = alertSessionExceded($_SESSION['request']);
    }

    $asteroidsCount = $neo->getAsteroidCount();
    $asteroidsAlerts = $neo->getAsteroidAlerts();
    $asteroidsAlertsCount = count($asteroidsAlerts);


?>

<!doctype html>
<html lang="es" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Ejercicio API NASA, Session, Conectar con BBDD">
    <meta name="author" content="Manuel Malvar">
    <title>NASA</title>

    <link rel="icon" sizes="16x16" type="image/png" href="src/img/favicon/favicon-16x16.png" />
    <link rel="icon" sizes="32x32" type="image/png" href="src/img/favicon/favicon-32x32.png" />
    <link rel="icon" sizes="57x57" type="image/png" href="src/img/favicon/favicon-57x57.png" />
    <link rel="icon" sizes="76x76" type="image/png" href="src/img/favicon/favicon-76x76.png" />
    <link href="src/styles/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" crossorigin="anonymous" />
    <link href="src/styles/styles.css" rel="stylesheet">
</head>
<body class="init layout">
    <section class="container-fluid d-flex flex-row px-0">
        <header class="header bg-black px-1 flex justify-items-between px-1 pv-2 w-100">
            <figure class="flex logo mb-0 px-3">
                <img class="mb-0" src="src/img/nasa-logo.png"
                     style="view-transition-name: logo-Nasa"
                     alt="LOGO Nasa"
                />
            </figure>
            <div class="d-flex">
                <div class="d-flex">
                    <form  class="d-flex form-signin" method="get" action="index.php">
                        <div class="input-group btn-group">
                            <input name="dateInput" class="form-control" type="date" placeholder="<?php echo $initDate ?>" value="<?php echo $initDate ?>"  id="dateInput" max="<?php echo date("Y-m-d") ?>">
                            <label for="dateInput"></label>
                            <button class="input-group-text bg-danger border-danger text-white text-uppercase">
                                <i class="bi bi-search mx-2"></i> Buscar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <ul class="dropdown m-0">
                <li class="nav-user">
                    <a class="d-flex align-items-center nav-link px-2" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="account-user avatar bg-danger me-3">
                            <span><?php echo $initialLetterUser ?></span>
                        </span>
                        <span class="d-lg-flex flex-column gap-1 d-none me-3 lh-1">
                            <span class="my-0">Hola</span>
                            <strong class="my-0 fw-normal"><?php echo $user ?> </strong>
                        </span>
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                        <button class="btn btn-link btn-link-danger" type="button" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="me-1 bi bi-box-arrow-right"></i>
                            <span>Cerrar sesión</span>
                        </button>
                    </div>
                </li>
            </ul>
        </header>
    </section>
    <section class="container-fluid info-user">
        <aside>
            <ul class="navbar-nav container d-block d-md-flex flex-row justify-content-between list-unstyled ">
                <li class="nav-item me-4 title">
                    <p class="m-0 nav-link">
                        <i class="me-1 bi bi-person"></i>
                        <strong class="text-white text-uppercase"><?php echo $user ?></strong>
                    </p>
                </li>
                <li class="nav-item me-4">
                    <p class="m-0 nav-link">
                        <i class="me-1 bi bi-send-check-fill"></i>
                        Total API Request: <strong class="text-white"><?php echo $rateLimit ?></strong>
                    </p>
                </li>
                <li class="nav-item me-4">
                    <p class="m-0 nav-link">
                        <i class="me-1 bi bi-send-arrow-down-fill"></i>
                        Remaining API Request: <strong class="text-white"><?php echo $rateRemaining ?></strong>
                    </p>
                </li>
                <li class="nav-item me-4">
                    <p class="m-0 nav-link">
                        <i class="me-1 bi bi-send-exclamation-fill"></i>
                        Request in session: <?php echo '<strong class="text-white">' . $_SESSION['request']. '</strong> / '. $TOTAL_REQUEST_PER_SESSION ?>
                    </p>
                </li>
            </ul>
        </aside>
    </section>
    <section class="container py-3">
        <div id="errorWrap">
            <?php if (!empty($alertSms)): ?>
                <?php echo $alertSms; ?>
            <?php endif; ?>
        </div>
        <p class="d-inline-flex gap-1">
            <a class="btn btn-light" data-bs-toggle="collapse" href="#collapseAsteroid" role="button" aria-expanded="false" aria-controls="collapseAsteroid">
                <span class="d-flex align-items-start">
                    <span class="h4 mx-2">Asteroides</span>
                    <span class="badge me-2 text-bg-info"> <i class="me-1 bi bi-eye-fill"></i> <?php echo $asteroidsCount ?></span>
                </span>
            </a>
        </p>
        <?php
            if ($asteroidsAlertsCount  > 0){
                echo '
                    <p class="d-inline-flex gap-1">
                        <a class="btn btn-danger" data-bs-toggle="collapse" href="#collapseAlerts" role="button" aria-expanded="false" aria-controls="collapseAlerts">
                            <span class="d-flex align-items-start">
                                <span class="h4 mx-2">Alertas</span>
                                <span class="badge text-bg-dark"><i class="me-1 bi bi-exclamation-triangle-fill"></i> '. $asteroidsAlertsCount . '</span>
                            </span>
                        </a>
                    </p>';
            } ?>
            <div class="collapse" id="collapseAsteroid">
                <div class="px-3">
                    <ul class="navbar-nav container d-block d-md-flex flex-row list-unstyled ">
                        <li class="nav-item me-4 title">
                            <p class="m-0 nav-link">
                                <i class="me-1 bi bi-eye-fill"></i> Detectados:
                                <strong class="text-info"><?php echo $asteroidsCount ?></strong>
                            </p>
                        </li>
                        <?php
                            if ($asteroidsAlertsCount > 0){
                                echo '
                                    <li class="nav-item me-4 title">
                                        <p class="m-0 nav-link">
                                            <i class="me-1 bi bi-exclamation-triangle-fill"></i> Alerta:
                                            <strong class="text-danger">'. $asteroidsAlertsCount . '</strong>
                                        </p>
                                    </li>';
                            }
                        ?>
                    </ul>
                </div>
            </div>


        <?php
            if ($asteroidsAlertsCount > 0){
                echo'<div class="collapse py-2" id="collapseAlerts">
                        <div class="px-3">
                            <div class="accordion-body">
                                <div class="meteor-warning d-flex flex-row flex-wrap row">';
                        foreach ($asteroidsAlerts as $asteroidsAlert) {
                            echo '
                                <div class="p-1 col-sm-6 col-lg-4 col-xl-3">
                                    <div class="card w-100 bg-danger text-bg-danger">
                                        <div class="card-body">
                                            <p class="card-title h5 mb-4"><strong>'. $asteroidsAlert->name .'</strong></p>
                                            <ul class="list-unstyled m-0">
                                                <li>
                                                    <p class="my-1"> 
                                                        <i class="me-1 bi bi-app-indicator"></i>
                                                        Orbit: <strong class="text-white">'. roundTwoDecimals($asteroidsAlert->close_approach_data[0]->orbiting_body) .'</strong>
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="my-1"> 
                                                        <i class="me-1 bi bi-arrows-expand-vertical"></i>
                                                        Diameter: <strong class="text-white">'. roundTwoDecimals($asteroidsAlert->estimated_diameter->kilometers->estimated_diameter_max) .'</strong> Km
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="my-1"> 
                                                        <i class="me-1 bi bi-speedometer"></i>
                                                        Speed: <strong class="text-white">'. roundTwoDecimals($asteroidsAlert->close_approach_data[0]->relative_velocity->kilometers_per_second) .'</strong> Km/s
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="my-1"> 
                                                        <i class="me-1 bi bi-rulers"></i>
                                                        Distance: <strong class="text-white">'. roundTwoDecimals($asteroidsAlert->close_approach_data[0]->miss_distance->lunar) .'</strong> Lunar
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            ';
                        }
                echo '</div>
                            </div>
                        </div>
                    </div>';
            }
        ?>

    </section>
    <section class="container  mt-3">
        <main class="bg-container p-3">
            <article>
                <h1 class="h2">
                    <?php echo isset($response->title)
                        ? $response->title
                        : ''  ?>
                </h1>
                <hr />
                <div class="d-md-flex row">
                    <div class="col-md-4">
                        <?php
                            echo mediaTypeView($response);
                        ?>
                    </div>
                    <div class="col-md-8">
                        <p>
                            <?php echo isset($response->explanation)
                                ? $response->explanation
                                : ''
                            ?>;
                        </p>
                    </div>
                </div>
            </article>
        </main>
    </section>
    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">¿Desea cerrar la sesión?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <form method="post">
                        <button name="logout" type="submit" class="btn btn-danger">Cerrar sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="src/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
