<?php
require_once "../class/Conexion.php";
require_once "../class/Producto.php";
require_once "../class/Usuario.php";
require_once "../views/utilities.php";

session_start();
//Comprobamos si somos un usuario invitado o normal
if (isset($_SESSION['nombre'])) {
    $usuario = $_SESSION['nombre'];
    $validado = true;
    $user = new Usuario();
    $response = $user->getUserId($usuario);
    $userId = $response;

} else {
    $usuario = "Invitado";
    $validado = false;
    $userId = null;
}
//Hacemos el autoload de las clases
spl_autoload_register(function ($class) {
    require "../class/" . $class . ".php";
});

// recuperamos los productos
$conexion = new Conexion();
$productos = new Producto($conexion);
$filas = $productos->obtenerProductos();

?>
<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
              integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
              integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <title>DWES07 - Ejercicio 5</title>

    </head>

    <body style="background: #4dd0e1">

    <div class="float float-right d-inline-flex mt-2">
        <i class="fas fa-user mr-3 fa-2x"></i>
        <input type="text" size='10px' value="<?php echo $usuario; ?>"
               class="form-control mr-2 bg-transparent text-white" disabled>
        <?php
        if ($validado)
            echo "<a href='cerrar.php' class='btn btn-danger mr-2'>Salir</a>";
        else
            echo "<a href='login.php' class='btn btn-primary mr-2'>Login</a>";
        ?>
    </div>
    <br><br>

        <h3 class="text-center mt-2 font-weight-bold">Gestión de Productos</h3>
        <?php
            if (isset($_SESSION['mensaje'])) {
                echo "<h4 class='container text-info'>";
                echo "<i class='fa fa-exclamation-triangle'></i> " . $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
                echo "</h4>";
            }
        ?>
        <?php
             echo "<div class='container mt-3'>";
            if ($validado)
                echo "<a href='repartos.php' class='btn btn-success mt-2 mb-2'>Administrar repartos <i class='fa fa-truck'></i></a>";
            echo "</div>";
        ?>
        <div class="container mt-3">
            <?php
            /*
            if ($validado)
                echo "<a href='crear.php' class='btn btn-success mt-2 mb-2'><i class='fa fa-plus'></i> Crear</a>";
            else
                echo "<a href='crear.php' class='btn btn-success mt-2 mb-2 disabled'><i class='fa fa-plus'></i> Crear</a>";
            */
                ?>
            <table class="table table-striped table-dark">
                <thead>
                    <tr class="text-center">
                        <th scope="col">Codigo</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Valoración</th>
                        <th scope="col">Valorar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (count($filas) == 0) {
                            echo "<tr><td colspan='4' class='text-center'>No hay productos</td></tr>";
                        } else{
                            foreach ($filas as $fila){
                                $query = $productos->valoracionProducto($fila['id']);
                                $valoracion = $query["valoracion"];
                                $totalVotos = $query["total_votos"];

                                echo "<tr class='text-center product'>";
                                    echo "<td class='align-middle'>{$fila['id']}</td>";
                                    echo "<td class='align-middle'>{$fila['nombre']}</td>";

                                    echo "<td class='align-middle valoration'>";
                                        printCellValoracion($valoracion, $totalVotos);
                                    echo "</td>";

                                    echo "<td class='align-middle'>";
                                        if ($validado) {
                                            echo "<form class='votacion-form'>
                                                    <select id='select-{$fila['id']}' class='form-select fa p-2' aria-label='Default select example' name='valoracion' id='valoracion' required>
                                                        <option value='1'>&#xf005;</option>
                                                        <option value='2'>&#xf005;&#xf005;</option>
                                                        <option value='3'>&#xf005;&#xf005;&#xf005;</option>
                                                        <option value='4'>&#xf005;&#xf005;&#xf005;&#xf005;</option>
                                                        <option value='5'>&#xf005;&#xf005;&#xf005;&#xf005;&#xf005;</option>
                                                    </select>
                                                    <input type='hidden' name='id' value='{$fila['id']}'>
                                                    <input type='hidden' name='usuario' value='{$usuario}'>
                                                    <input type='hidden' name='userId' value='{$userId}'>
                                                    <input type='hidden' name='totalVotos' value='{$totalVotos}'>
                                                    <button class='btn btn-primary btnVotar m-2' value='Valorar'>Votar</button>
                                                </form> ";
                                        } else {
                                            echo "Debe Registrarse";
                                        }
                                    echo "</td>";
                                echo "</tr>";
                            }
                        }
                        $stmt = null;
                    ?>
                </tbody>
            </table>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.btnVotar').on('click', function (event) {
                    event.preventDefault();

                    let form = $(this).closest('.votacion-form')
                    let valorationCell = $(this).closest('.product').find('.valoration')

                    let idProducto = form.find('input[name="id"]').val() // Obtiene el valor del input 'id'
                    let usuario = form.find('input[name="usuario"]').val() // Obtiene el valor del input 'usuario'
                    let userId = form.find('input[name="userId"]').val() // Obtiene el valor del input 'userId'
                    let totalVotos = form.find('input[name="totalVotos"]').val() // Obtiene el valor del input 'totalVotos'
                    let valoracion = form.find('select[name="valoracion"]').val() // Obtiene el valor seleccionado del select

                    $.ajax({
                        url: '../api/miVoto.php',
                        type: 'POST',
                        data: {
                            id_producto: idProducto,
                            usuario: usuario,
                            userId: userId,
                            totalVotos: totalVotos,
                            valoracion: valoracion
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                // Aquí actualizamos el HTML para reflejar que la valoración se ha registrado.
                                // Por ejemplo, reemplazamos el contenido de la celda actual con un mensaje.
                                form.closest('td').html('<span class="text-success">¡Gracias por tu valoración!</span>');
                                valorationCell.html(response.numVotes + (response.numVotes > 1 ? ' valoraciones: ' : ' valoración: ') + printStars(response.newRating));

                            } else if (response.error) {
                                alert(response.error);
                            }
                        },
                        error: function () {
                            alert('Error al enviar la valoración. Inténtalo de nuevo.');
                        }
                    });
                });
            });

            function printStars(num){
                let fillStars = Math.floor(num);
                let stars = "";
                for (let i = 0; i < fillStars; i++) {
                    stars += "<i class='fa fa-star'></i>";
                }
                if (num - fillStars > 0) {
                    stars += "<i class='fa fa-star-half'></i>";
                }
                return stars;
            }

        </script>
    </body>
</html>