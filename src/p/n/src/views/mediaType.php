<?php

function mediaTypeView($type) {
    $media = '';
    if( !isset($type) ){
        echo '<p class="h3">No se encontraron resultados.</p>';
    }
    if ( $type->media_type == 'image' ) {
        $copyright = isset($type->copyright)
            ? $type->copyright
            : 'Sin derechos registrados';

        $hdurl = isset($type->hdurl)
            ? $type->hdurl
            : '';

        $url = isset($type->url)
            ? $type->url
            : '';

        $media = '
            <figure>
                <a href="' . $hdurl . '" target="_blank">
                    <img class="img-thumbnail" src="' . $url . '" alt="title" width="auto" height="auto">
                </a>
                <figcaption class="figure-caption text-end">
                   <i> &copy;Copyright: ' . $copyright . '</i>
                </figcaption>
            </figure>
            <a href="' . $hdurl . '" class="btn w-100 mb-3 btn-danger" download>
                <i class="bi bi-download me-1"></i>
                Descargar imagen
            </a>
        ';
    }

    if ( $type->media_type == 'video' ) {
        $media = '<iframe width="auto" height="315" src="' . $url . '"  allowfullscreen></iframe>';
    }

    return $media;
}