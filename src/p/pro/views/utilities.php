<?php
include_once "../class/RepartoProductos.php";

$conexion = new Conexion();
$repartoProducto = new RepartoProductos($conexion);

function printCellValoracion($valoracion, $totalVotos){
    if($valoracion == null){
        echo "Sin Valoraciones";
    } else{
        echo $totalVotos;
        echo (ceil($totalVotos) > 1) ? " valoraciones: " : " valoración: ";
        echo printStars($valoracion);
    }
}


function printStars($num){
    $fillStars = floor($num);
    $stars = "";
    for ($i = 0; $i < $fillStars; $i++) {
        $stars .= "<i class='fa fa-star'></i>";
    }
    if ($num - $fillStars > 0) {
        $stars .= "<i class='fa fa-star-half'></i>";
    }
    return $stars;
}

function printAllRepartos($allRepartos){
    global $repartoProducto;
    if (count($allRepartos) == 0) {
        echo "<div id='notFoundRepartos' class='d-flex align-items-center justify-content-between bg-secondary p-3 '>
                        <span class='text-light'>
                            No hay repartos registrados
                        </span>
                    </div>";
    } else {
        foreach ($allRepartos as $reparto) {

            $productos = $repartoProducto->obtenerProductos($reparto['id']);

            echo  "<div class='repartoItem my-3' id='{$reparto['id']}'>
                        <div class='d-flex align-items-center justify-content-between bg-secondary p-3 '>
                            <span class='text-light'>
                                Repartos: {$reparto['fecha']}
                            </span>
                            <div>
                                <div class='btn-group' role='group' aria-label='Basic example'>
                                    <button type='button' class='btn btn-info btnNuevoProductoRepartir' data-toggle='modal' data-target='.bd-example-modal-xl'><i class='fa fa-plus mr-2'></i>Nuevo</button>
                                    <button type='button' class='btn btn-success btnOrdenarProductoRepartir'><i class='bi bi-list mr-2'></i> Ordenar</button>
                                    <a type='button' class='btn btn-light' data-toggle='collapse' href='#collapse{$reparto['id']}' role='button' aria-expanded='false' aria-controls='collapse{$reparto['id']}'>
                                        <i class='fa fa-eye mx-2'></i> | <i class='fa fa-eye-slash mx-2'></i></a>
                                    <button type='button' class='btn btn-danger btnBorrarReparto'><i class='fa fa-trash mr-2'></i> Borrar</button>
                                </div>
                            </div>
                        </div>
                        <div class='collapse' id='collapse{$reparto['id']}'>
                            <ul class='list-group'>";

            foreach ($productos as $producto) {
                if (!is_array($producto)) {
                    var_dump($producto);
                }
                echo    printProductoReparto($producto);
            }
            echo "
                           </ul>
                        </div>
                   </div>";
        }
    }
}

function printProductoReparto($producto){
    return " <li class='list-group-item w-100'
                data-lat='{$producto['lat_gps']}' 
                data-long='{$producto['long_gps']}' 
                data-idProducto='{$producto['id_producto']}' 
                data-idReparto='{$producto['id_reparto']}'
                data-direccion='{$producto['direccion']}'>
                <div class='w-100  d-flex align-items-center justify-content-between'>
                    <div>{$producto['nombre_producto']}</div>
                    <div>
                        <div class='btn-group' role='group' aria-label='Basic example'>
                            <button type='button' class='btn btn-danger btnBorrarProductoReparto'>
                                <i class='fa fa-trash mr-2'></i> Borrar
                            </button>
                            <button type='button' class='btn btn-info btnMapaProductoReparto' 
                                    data-toggle='modal'
                                    data-target='.bd-map-modal'>
                                <i class='fa fa-map mr-2'></i> Mapa
                            </button>
                        </div>
                    </div>
                </div>
             </li>";
}




?>