<?php
namespace TECWEB\MYAPI;

class DataBase {
    protected $conexion;

    // Constructor con valores por defecto
    public function __construct(
        $user = 'root',
        $pass = '',
        $db   = 'marketzone'
    ) {
        $this->conexion = new \mysqli('127.0.0.1', $user, $pass, $db);

        if ($this->conexion->connect_error) {
            die('Error de conexión: ' . $this->conexion->connect_error);
        }

        // Opcional: forzar UTF-8
        $this->conexion->set_charset('utf8mb4');
    }
}
