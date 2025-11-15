<?php
namespace TECWEB\MYAPI;

abstract class DataBase {
    protected $conexion;

    public function __construct($db, $user = 'root', $pass = '', $host = 'localhost') {
        $this->conexion = @mysqli_connect($host, $user, $pass, $db);
        if(!$this->conexion) {
            die('¡Base de datos NO conectada!');
        }
        $this->conexion->set_charset("utf8");
    }

    public function __destruct() {
        if($this->conexion) {
            mysqli_close($this->conexion);
        }
    }
}
?>