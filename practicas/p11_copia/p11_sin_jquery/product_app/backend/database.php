<?php
    $conexion = @mysqli_connect(
        'localhost',
        'root',
        'Dyrco_CRA13',  // TU PASSWORD
        'marketzone'
    );

    /**
     * NOTA: si la conexión falló $conexion contendrá false
     **/
    if(!$conexion) {
        die('¡Base de datos NO conectada!');
    }
?>