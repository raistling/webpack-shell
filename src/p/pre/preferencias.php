<?php
global $NO_ESTABLECIDO;
include_once 'utils/config.php';
include_once 'utils/printMessages.php';

$infoMessage = '';

$lang = $NO_ESTABLECIDO;
$profile = $NO_ESTABLECIDO;
$timezone = $NO_ESTABLECIDO;

session_start();

if( isset($_SESSION['lang']) ){
    $lang = $_SESSION['lang'];
}
if( isset($_SESSION['profile']) ){
    $profile = $_SESSION['profile'];
}
if( isset($_SESSION['timezone']) ){
    $timezone = $_SESSION['timezone'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $lang = $_POST['lang'];
    $profile = $_POST['profile'];
    $timezone = $_POST['timezone'];

    empty($lang)
        ? $infoMessage .= errorSelectLang()
        : $_SESSION['lang'] = $lang;

    empty($profile)
        ? $infoMessage .= errorSelectProfile()
        : $_SESSION['profile'] = $profile;

    empty($timezone)
        ? $infoMessage .= errorSelectTimezone()
        : $_SESSION['timezone'] = $timezone;

    if( isset($_SESSION['lang']) &&
        isset($_SESSION['profile']) &&
        isset($_SESSION['timezone'] )){
        $infoMessage = preferencesSaved();
    }
}
?>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Preferencias</title>
        <link rel="stylesheet" href="src/styles/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    </head>
    <body class="bg-light">
        <div class="container d-flex justify-content-center py-5">
            <div class="card" style="width: 30rem;">
                <form method="post" action="preferencias.php" class="mb-0">
                    <div class="card-header">
                        <h1 class=" h4">Preferencias de usuario</h1>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold" for="lang">Idioma</label>
                            <div class="input-group mb-3">
                                <i class="input-group-text bi bi-translate flex"></i>
                                <select name="lang" class="form-select" id="lang">
                                    <option value="">Selecciona Idioma...</option>
                                    <?php
                                        global $LANGUAGES;
                                        foreach($LANGUAGES as $lang){
                                            if($lang == $_SESSION['lang']){
                                                echo '<option value="'.$lang.'" selected>'.$lang.'</option>';
                                            }else{
                                                echo '<option value="'.$lang.'">'.$lang.'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold" for="profile">Perfil público</label>
                            <div class="input-group mb-3">
                                <i class="input-group-text bi bi-people-fill flex"></i>
                                <select name="profile" class="form-select" id="profile">
                                    <option value="">Elige un perfil...</option>
                                    <?php
                                        global $PROFILES;
                                        foreach($PROFILES as $profile){
                                            if($profile == $_SESSION['profile']){
                                                echo '<option value="'.$profile.'" selected>'.$profile.'</option>';
                                            }else{
                                                echo '<option value="'.$profile.'">'.$profile.'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold" for="timezone">Zona horaria</label>
                            <div class="input-group mb-3">
                                <i class="input-group-text bi bi-clock flex"></i>
                                <select name="timezone" class="form-select" id="timezone">
                                    <option value="">Seleccione zona horaria...</option>
                                    <?php
                                    global $TIMEZONE;
                                    foreach($TIMEZONE as $zone){
                                        if($zone == $_SESSION['timezone']){
                                            echo '<option value="'.$zone.'" selected>'.$zone.'</option>';
                                        }else{
                                            echo '<option value="'.$zone.'">'.$zone.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <?php
                                echo isset($infoMessage) && !empty($infoMessage) ? $infoMessage : '';
                            ?>
                        </div>
                    </div>
                    <div class="card-footer justify-content-between d-flex align-content-between w-100 py-3">
                        <a href="mostrar.php" class="btn btn-primary">Mostrar preferencias</a>
                        <button type="submit" class="btn btn-success">Establecer preferencias</button>
                    </div>
                </form>
            </div>
        </div>


        <script src="src/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    </body>
</html>