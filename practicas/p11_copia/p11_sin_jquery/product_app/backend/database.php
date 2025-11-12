<?php
$conexion = @mysqli_connect(
    'localhost', // Servidor
    'root',      // Usuario por defecto de XAMPP
    '',          // Sin contraseña
    'marketzone' // Nombre de la base
);

if (!$conexion) {
    die('¡Base de datos NO conectada!');
}
?>
