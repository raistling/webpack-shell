<?php

function errorSelectLang(){
    return '<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:2rem; height:1rem;" class="bi">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <strong>Debe seleccionar un idioma</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
}

function errorSelectProfile(){
    return '<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:2rem; height:1rem;" class="bi">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <strong>Debe seleccionar un perfil</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
}

function errorSelectTimezone(){
    return '<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:2rem; height:1rem;" class="bi">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <strong>Debe seleccionar una zona horaria</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
}

function preferencesSaved(){
    return '<div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:2rem; height:1rem;" class="bi">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                <strong>¡Preferencia de usuario guardadas!</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
}

function preferencesDeleted(){
    return '<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mt-3" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:2rem; height:1rem;" class="bi">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <strong>¡Preferencias Borradas!</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
}

function preferencesEmpty(){
    return '<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mt-3" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:2rem; height:1rem;" class="bi">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <strong>¡Debes fijar primero las preferencias!</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
}