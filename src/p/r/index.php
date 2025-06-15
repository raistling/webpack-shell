<?php
    session_start();

    // si recibe el form de logout redirige a login, y destruye la sesión
    if (isset($_POST["logout"])) {
        session_unset(); // Elimina todas las variables de sesión
        session_destroy(); // Destruye la sesión
        header("Location: login.php"); // Redirige al inicio de sesión
        exit();
    }

    // Si no existe sesión directamente redirecciona a login.php
    if (!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit();
    }


    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Llamada a la API de Rick y Morty
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


    // request de formulario de página específica
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["go"])) {
        $page = intval($_POST["page"]); // Convertir el valor a número

        // reescriben las variables que hidratan a la web.
        $apiUrl = "https://rickandmortyapi.com/api/character?page=".$page;
        $response = file_get_contents($apiUrl);
        $data = json_decode($response, true);

        // Si el número ingresado existe en el array, redirigir
        if ($page < $totalPages && $page > 0) {

            header("Location: ?page=". $page);

        } else {
            echo "Número inválido. Por favor, ingresa un número válido.";
        }
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

        <div class="container mt-5">
            <div class="d-flex justify-content-center align-items-center mt-4">
                <div class="w3-show-inline-block text-center">
                    <!-- Controles de Navegación -->
                    <div class="d-flex flex-wrap justify-content-between mt-4">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>" class="btn btn-primary mx-1">Anterior</a>
                        <?php endif; ?>
                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>" class="btn btn-primary mx-1">Siguiente</a>
                        <?php endif; ?>
                    </div>
                    <!-- Paginación con Bootstrap -->
                    <nav class="d-flex flex-wrap">
                        <ul class="pagination flex-wrap justify-content-center">
                            <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                                <a class="page-link" href="?page=<?= $page - 1 ?>">Anterior</a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
                                <a class="page-link" href="?page=<?= $page + 1 ?>">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="text-center">
                    <!--TODO: Add requests -->
                    <form method="post">
                        <label for="page">Ingresa un número:</label>
                        <input type="number" id="page" name="page" required>
                        <button type="submit" name="go">Ir a la página</button>
                    </form>
                </div>
                <!--TODO: Add the form to logout-->
                <form action="logout.php" method="post">
                    <button class="btn btn-danger" type="submit">Cerrar sesión</button>
                </form>

            </div>
            <br>
            <div class="row">
                <?php foreach ($data["results"] as $character): ?>
                    <div class="col-md-4 text-center">
                        <img src="<?= $character["image"] ?>" alt="<?= $character["name"] ?>" class="img-fluid rounded-circle">
                        <p><strong><?= $character["name"] ?></strong></p>
                        <p><strong><?= $character["location"]["url"] ?></strong></p>
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