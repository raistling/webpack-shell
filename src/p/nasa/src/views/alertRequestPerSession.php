<?php

function alertRequestPerSessionView($request){
    global $TOTAL_REQUEST_PER_SESSION;
    return '
        <div class="alert alert-warning d-flex alert-dismissible" role="alert">
            <i class="bi bi-exclamation-triangle-fill mx-2"></i>
            <div>
                Solo te quedan ' .($TOTAL_REQUEST_PER_SESSION - $request). ' peticiones disponibles en la sesión.
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    ';
}