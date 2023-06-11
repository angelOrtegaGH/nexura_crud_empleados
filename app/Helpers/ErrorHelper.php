<?php

function errors_sql($code, $clave, $valor){
    switch ($code) {
        case '23000':
            $error_msj = "Ya existe un $clave registrado con $valor";
            break;

        default:
            $error_msj = "Error desconocido por favor contacte al administrador ($code)";
            break;
    }
    return $error_msj;
}
