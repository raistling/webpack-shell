<?php
    session_start();
    if (!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit();
    }

    // Llamada a la API de Rick y Morty
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    
    $apiUrl = "https://rickandmortyapi.com/api/character?page=".$page;
    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);
    $totalPages = $data['info']['pages'];

   if(isset($_GET['page'])){
        $apiUrl = "https://rickandmortyapi.com/api/character?page=".$page;
        $response = file_get_contents($apiUrl);
        $data = json_decode($response, true);        
    } 
    if(intval($_GET['page']) > $totalPages){
        header("Location: ?page=". $totalPages);
    }
    if(intval($_GET['page']) < 1){
        header("Location: ?page=". 1);
    }

 



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rick y Morty</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <img src="rickymorty.png" class="rounded mx-auto d-block" onclick="location.href='.';">
        <div class="container mt-5">
            <div class="d-flex justify-content-center align-items-center mt-4">
                <div class="w3-show-inline-block text-center">
                    <!-- Controles de Navegación -->
                    <div class="d-flex justify-content-between mt-4">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>" class="btn btn-primary mx-1">Anterior</a>
                        <?php endif; ?>
                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>" class="btn btn-primary mx-1">Siguiente</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="text-center">
                    <!--TODO: Add requests -->
                </div>
                <!--TODO: Add the form to logout-->
                <a href="login.php" class="btn btn-danger mt-4 ms-3">Cerrar sesión</a> 

            </div>
            <br>
            <div class="row">
                <?php foreach ($data["results"] as $character): ?>
                    <div class="col-md-4 text-center">
                        <img src="<?= $character["image"] ?>" alt="<?= $character["name"] ?>" class="img-fluid rounded-circle">
                        <p><strong><?= $character["name"] ?></strong></p>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-4">
                <div class="w3-show-inline-block" align="center">
                    <!--TODO: Add the buttons to navigate between pages-->
                    <div class="d-flex justify-content-center mt-4">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>" class="btn btn-primary mx-1">Anterior</a>
                        <?php endif; ?>
                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>" class="btn btn-primary mx-1">Siguiente</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <br>
        </div>
    <!-- Bootstrap JS and Popper.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>