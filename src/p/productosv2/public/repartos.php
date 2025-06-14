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
$repartos = new Reparto($conexion);
$rp = new RepartoProductos($conexion);
$allProductos = $productos->obtenerProductos();
$allRepartos = $repartos->obtenerPedidos();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DWES08</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body{
            background: #4dd0e1;
        }
        #map { height: 400px; }
        .mapContainerList li:after{
            font-family: "bootstrap-icons";
            content: '\F138';
            position: absolute;
            top: 50%;
            margin-top: -10px;
            height: 21px;
            width: 20px;
            right: 0;
            margin-right: -13px;
            z-index: 2;
            line-height: 22px;
            background-color: white;
        }
        .mapContainerList li:last-child:after{
            content: "";
            display: none;
        }
    </style>
        </head>
<body>

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

    <h3 class="text-center mt-2 font-weight-bold">Gestión de Repartos</h3>
    <?php
    if (isset($_SESSION['mensaje'])) {
        echo "<h4 class='container text-info'>";
        echo "<i class='fa fa-exclamation-triangle'></i> " . $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
        echo "</h4>";
    }
    ?>
    <div class="container mt-3">
        <div id="statusInfo"></div>
        <form class='votacion-form' id="formNuevoReparto">
            <div class="input-group mb-3">
                <button class='btn btn-info btnNuevoReparto' value='Valorar'><i class='fa fa-plus mr-2'></i> Nueva Lista de reparto</button>
                <input type="date" name="fechaReparto" class="form-control" id="fechaReparto" placeholder="Seleccionar fecha"  aria-label="Seleccionar fecha" aria-describedby="basic-addon2">
            </div>
        </form>
        <div id="wrapperRepartos">
            <?php
                printAllRepartos($allRepartos);
            ?>
        </div>
    </div>

    <div id="mapModal" class="modal fade bd-map-modal" tabindex="-1" role="dialog" aria-labelledby="bd-map-modal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div id="mapWrapper" class="modal-body">
                   <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="alertForm"></div>
                    <div class="form-group ">
                        <div class="input-group my-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fa fa-building"></i>
                                </div>
                            </div>
                            <input type="text" name="direccion" class="form-control" id="address" placeholder="Introduce una dirección" />
                            <button id="search" class="btn btn-primary">Ver Coordenadas</button>
                        </div>
                        <div class="input-group my-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fa fa-map"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="lat" name="lat" placeholder="Latitud" disabled/>
                        </div>
                        <div class="input-group my-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fa fa-map"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="long" name="long" placeholder="Longitud" disabled />
                        </div>
                        <div class="form-group">
                            <select name="productoId" class="form-control" id="productoId">
                                <option value="">Selecciona producto</option>
                                <?php
                                foreach($allProductos as $producto)
                                {
                                    echo '<option value="'.$producto["id"].'">'.$producto["nombre"].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="repartoId" id="repartoId">
                        <input type="hidden" name="nombreProductoSelected" id="nombreProductoSelected">
                        <button id="btnAddProductoReparto" class="btn btn-success w-100">
                            <i class="fa fa-plus"></i> Añadir entrega
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.3/js/bootstrap.min.js" integrity="sha512-8qmis31OQi6hIRgvkht0s6mCOittjMa9GMqtK9hes5iEQBQE/Ca6yGE5FsW36vyipGoWQswBj/QBm2JR086Rkw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.min.js" integrity="sha512-FW2A4pYfHjQKc2ATccIPeCaQpgSQE1pMrEsZqfHNohWKqooGsMYCo3WOJ9ZtZRzikxtMAJft+Kz0Lybli0cbxQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>

        // Modal input fecha. Día mínimo: hoy
        let today = new Date().toISOString().split('T')[0];
        document.getElementById('fechaReparto').setAttribute('min', today);

        // Contenedor para mensajes globales
        let statusInfo = $('#statusinfo')

        // Recuperar el texto del select con el producto
        $("#productoId").change(function() {
            var nombreProducto = $(this).find(':selected').text();
            $("#nombreProductoSelected").val(nombreProducto);
        });

        /* IMPORTANTE: al crear el elemento dinámicamente, para que funcione un evento creado posterior al DOM incial,
           se debe hacer con delegación de eventos. document -> indicarle el selector */

        // Lista Reparto: Eliminamos una Lista de Repartos
        $(document).on("click", ".btnBorrarReparto", function () {
            let $item = $(this).closest(".repartoItem")
            let id = $item.attr("id")
            let confirmDelete = confirm("¿Estás seguro de que quieres eliminar este reparto? \nSe eliminarán todos los productos asociados al reparto");

            if (confirmDelete) {
                $.ajax({
                    url: '../api/borrarReparto.php',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            statusInfo.html(`
                            <div class='alert my-3 alert-success alert-dismissible fade show' role='alert'>
                                <strong>${response.message}</strong>
                                <button type='button' class='close' data-dismiss='alert' aria-label='Cerrar'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>`);
                            $item.remove()

                        } else if (response.error) {
                            statusInfo.html(`
                        <div class='alert my-3 alert-danger alert-dismissible fade show' role='alert'>
                              <strong>${response.error}</strong>
                              <button type='button' class='close' data-dismiss='alert' aria-label='Cerrar'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>`);
                        }
                    },
                    error: function () {
                        alert('Error al enviar la petición. Inténtalo de nuevo.');
                    }
                })
            }
        })

        // Lista Reparto: Añadimos una nueva Lista de Reparto
        $(document).on("click", ".btnNuevoReparto", function (e) {
            e.preventDefault();
            let fechaReparto = $("#fechaReparto").val() // Obtiene el valor del input 'fechaReparto'

            $.ajax({
                url: '../api/nuevoReparto.php',
                type: 'POST',
                data: {
                    fechaReparto: fechaReparto
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        // Aquí actualizamos el HTML para reflejar que la valoración se ha registrado.
                        // Por ejemplo, reemplazamos el contenido de la celda actual con un mensaje.
                        statusInfo.html("<div class='alert my-3 alert-success alert-dismissible fade show' role='alert'>" +
                                            "<strong>Reparto añadido</strong> " +
                                            "<button type='button' class='close' data-dismiss='alert' aria-label='Cerrar'><span aria-hidden='true'>&times;</span></button> " +
                                        "</div>");

                        let reparto = response.repartos

                        let templateReparto =
                            `<div class="repartoItem my-3" id="${reparto.id}">
                                <div class="d-flex align-items-center justify-content-between bg-secondary p-3">
                                    <span class="text-light">Reparto: ${reparto.fecha}</span>
                                    <div>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-info btnNuevoProductoRepartir" data-toggle='modal' data-target='.bd-example-modal-xl'><i class="fa fa-plus mr-2"></i>Nuevo</button>
                                            <button type="button" class="btn btn-success btnOrdenarProductoRepartir"><i class="bi bi-list mr-2"></i>Ordenar</button>
                                            <a type="button" class="btn btn-light" data-toggle="collapse" href="#collapse${reparto.id}" role="button">
                                                 <i class="fa fa-eye-slash mx-2"></i> | <i class="fa fa-eye mx-2"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btnBorrarReparto"><i class="fa fa-trash mr-2"></i> Borrar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="collapse" id="collapse${reparto.id}">
                                    <ul class="list-group">
                                    </ul>
                                </div>
                            </div>`;

                        $("#notFoundRepartos").remove()
                        $("#wrapperRepartos").prepend(templateReparto)

                    } else if (response.error) {
                        alert(response.error);
                    }
                },
                error: function () {
                    alert('Error al enviar la petición. Inténtalo de nuevo.');
                }
            });
        });

        // Reparto: Botón "Nuevo" producto a Repartir
        $(document).on("click", ".btnNuevoProductoRepartir", function (e) {
            e.preventDefault();

            //recogemos el ID del reparto
            var repartoId = $(this).closest('.repartoItem').attr('id');

            $("#repartoId").val(repartoId);

            $("#search").click(function() {
                var address = $("#address").val();
                var url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`;

                $.getJSON(url, function(data) {
                    if (data.length > 0) {
                        var lat = data[0].lat;
                        var lon = data[0].lon;

                        // Actualizar los inputs con las coordenadas
                        $("#lat").val(lat);
                        $("#long").val(lon);

                    } else {
                        alert("Dirección no encontrada.");
                    }
                }).fail(function() {
                    console.error("Error en la solicitud de geocodificación.");
                });
            });
        });

        // Reparto Modal: Añadir producto al reparto
        $(document).on("click", "#btnAddProductoReparto", function (e) {
            e.preventDefault();
            let repartoId = $("#repartoId").val();
            let productoId = $("#productoId").val();
            let nombreProducto = $("#nombreProductoSelected").val();
            let direccion = $("#address").val();
            let lat = $("#lat").val();
            let long = $("#long").val();

            if(productoId == ''){

                $(".alertForm").empty().html(`<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>Debe seleccionar un producto
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>`)
            }else if(direccion == ''){
                $(".alertForm").empty().html(`<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>Debe indicar una dirección
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>`)
            }else{
                $.ajax({
                    url: '../api/nuevoProductoReparto.php',
                    type: 'POST',
                    data: {
                        repartoId: repartoId,
                        productoId: productoId,
                        lat: lat,
                        long: long,
                        direccion: direccion
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {

                            let acordeon = $(`#collapse${repartoId}`)
                            acordeon.collapse('show') //Mostramos el acordeon al añadir un producto si estuviera oculto
                            acordeon.find(".list-group").append(
                                ` <li class='list-group-item d-flex align-items-center justify-content-between' data-lat='${lat}' data-long='${long}' data-idProducto='${productoId}' data-idReparto='${repartoId}' data-direccion='${direccion}'>
                                    <div>${nombreProducto}</div>
                                    <div>
                                        <div class='btn-group' role='group' aria-label='Basic example'>
                                            <button type='button' class='btn btn-danger btnBorrarProductoReparto'><i class='fa fa-trash mr-2'></i> Borrar</button>
                                            <button type='button' class='btn btn-info btnMapaProductoReparto'><i class='fa fa-map mr-2'></i>Mapa</button>
                                        </div>
                                    </div>
                                </li>`)

                            //Mostramos mensaje de Elemento añadido
                        $("#collapse"+repartoId).append(`<div class="alert my-3 alert-success alert-dismissible fade show" role="alert">
                                                Añadido <strong>${nombreProducto}</strong> al reparto
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                            </div>`);

                            //Reseteamos los campos y ocultamos el modal una vez añadido
                            $("#repartoId").val("");
                            $("#productoId").val("");
                            $("#address").val("");
                            $("#lat").val("");
                            $("#long").val("");
                            $('.bd-example-modal-xl').modal('hide')

                        } else if (response.error) {
                            $(".alertForm").empty()
                            $(".alertForm").html(`<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>${response.error}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>`)
                        }
                    },
                    error: function () {
                        $(".alertForm").empty()
                        $(".alertForm").html(`<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>${response.error}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>`)
                    }
                });

            }

        })

        // Producto: Botón de borrar producto del reparto
        $(document).on("click", ".btnBorrarProductoReparto", function (e) {
            e.preventDefault();
            const $li = $(this).closest("li");

            // Obtiene los valores de los atributos personalizados
            let productoId = $li.data("idproducto");
            let repartoId = $li.data("idreparto");

            let confirmDelete = confirm("¿Estás seguro de que quieres eliminar este producto?");

            if (confirmDelete) {
                if(productoId == ''){
                    $(".alertForm").empty()
                    $(".alertForm").html(`<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <strong>Debe seleccionar un producto
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>`)
                }else if(repartoId == ''){
                    $(".alertForm").empty()
                    $(".alertForm").html(`<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <strong>Debe indicar una dirección
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>`)
                }else{
                    $.ajax({
                        url: '../api/borrarProductoReparto.php',
                        type: 'POST',
                        data: {
                            repartoId: repartoId,
                            productoId: productoId,
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                //Mostramos mensaje de Elemento añadido
                                $li.remove();

                            } else if (response.error) {
                                $(".alertForm").empty()
                                $(".alertForm").html(`<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <strong>${response.error}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>`)
                            }
                        },
                        error: function () {
                            $(".alertForm").empty()
                            $(".alertForm").html(`<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <strong>${response.error}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>`)
                        }
                    });

                }
            }

        })

        // Producto: Mostrar mapa en el Modal
        $(document).on("click", ".btnMapaProductoReparto", function (e){
            let mapa;

                let $li = $(this).closest("li")


                // Obtiene los valores de los atributos personalizados
                let latitud = $li.data("lat")
                let longitud = $li.data("long")

                $("#map").html("");
                if (mapa) {
                    mapa.remove(); // Método para destruir el mapa y evitar duplicados
                }
            $('#mapModal').modal('show').on('shown.bs.modal', function () {
                // Limpiar el contenedor del mapa para evitar problemas

                $("#map").remove(); // Elimina el contenedor
                $("#mapWrapper").append('<div id="map" style="width: 100%; height: 400px;"></div>'); // Recréalo


                // Si el mapa ya existe, eliminarlo antes de crear uno nuevo
                if (mapa) {
                    mapa.remove();
                }

                // Inicializar el mapa con las nuevas coordenadas
                mapa = L.map('map').setView([latitud, longitud], 12);

                // Agregar capa de mapa base de OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(mapa);

                // Agregar marcador en la ubicación específica
                L.marker([latitud, longitud]).addTo(mapa)
                    .bindPopup("<b>Ubicación seleccionada</b>").openPopup();
            });
        })

        // Reparto: Boton Ordenar Ruta
        $(document).on("click", ".btnOrdenarProductoRepartir", function () {
            let mapRoute = null

            let $reparto = $(this).closest(".repartoItem"); // Encuentra el reparto al que pertenece el botón
            let $productos = $reparto.find(".list-group-item"); // Encuentra todos los <li> dentro del reparto

            let mapid = "map" + $reparto.attr("id")

            let repartoId = $reparto.attr("id");
            console.log(repartoId);
            let acordeon = $(`#collapse${repartoId}`)
            acordeon.collapse('show')

            // Eliminamos contenedor previo e instancias previas
            $reparto.find(`#${mapid}`).remove();
            $reparto.find(".mapContainer").remove();

            // Si ya existe un mapa, destruirlo antes de crear uno nuevo
            if (mapRoute) {
                mapRoute.remove()
            }

            // Creamos los contenedores
            $reparto.find(".list-group").append("<li class='list-group-item mapContainer p-0'></li>")
            $reparto.find(".mapContainer").append(`<div id="${mapid}" style="width: 100%; height: 300px;"></div>`);
            $reparto.find(".mapContainer").prepend(`
            <div class="m-2 stepRoute">
                <p class="my-3 font-weight-bold text-center">Calculando Ruta optima</p>
                <ul class="list-group mb-2 list-group-horizontal-lg mapContainerList">

                </ul>
            </div>
            `);

            // Recorre cada producto y obtiene sus coordenadas
            let coordenadas = [] // Array para guardar las coordenadas

            // IES Teis GPS
            let lat_gps_Store = "42.251236"
            let lon_gps_Store = "-8.689823"
            let gps_Store = [lat_gps_Store,lon_gps_Store]

            // Añadimos las coordenadas de la tienda en el inicio/fin y los productos en el intermedio
            $reparto.find(".mapContainerList").append(`
                        <li class="list-group-item"><i class="bi bi-pin-map mr-2"></i>IES Teis</li>
                    `)
            coordenadas.push(gps_Store)
            $productos.each(function () {
                let latitud = $(this).data("lat")
                let longitud = $(this).data("long")
                let address = $(this).data("direccion")

                if (latitud && longitud) { // Solo si existen coordenadas válidas
                    coordenadas.push([latitud, longitud]) // Guardar en el array
                    $reparto.find(".mapContainerList").append(`
                        <li class="list-group-item">${address}</li>
                    `)
                }
            });
            coordenadas.push(gps_Store)
            $reparto.find(".mapContainerList").append(`
                        <li class="list-group-item">IES Teis <i class="bi bi-flag ml-2"></i></li>
                    `)

            // Inicializar nuevo mapa centrado en la primera coordenada
            mapRoute = L.map(mapid).setView(coordenadas[0], 13);

            // Agregar capa de OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(mapRoute);

            // Eliminar ruta anterior si existe
            let controlRuta = null;
            if (controlRuta) {
                mapRoute.removeControl(controlRuta);
            }

            // Crear la ruta usando Leaflet Routing Machine
            controlRuta = L.Routing.control({
                waypoints: coordenadas, // Lista de puntos de ruta
                routeWhileDragging: true,
                lineOptions: { styles: [{ color: 'blue', opacity: 0.7, weight: 5 }] },
                createMarker: function (i, waypoint, n) {
                    return L.marker(waypoint.latLng).bindPopup("Punto " + (i + 1));
                }
            }).addTo(mapRoute);
        });

</script>
</body>
</html>