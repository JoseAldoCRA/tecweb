<?php
    error_reporting(0); // Agregar esta línea
    
    $conexion = @mysqli_connect(
        'localhost',
        'root',
        'Dyrco_CRA13',
        'marketzone'
    );

    if(!$conexion) {
        die(json_encode([
            'status' => 'error',
            'message' => '¡Base de datos NO conectada!'
        ]));
    }
?>