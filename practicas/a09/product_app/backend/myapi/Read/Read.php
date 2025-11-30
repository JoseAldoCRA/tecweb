<?php
namespace TECWEB\MYAPI\Read;

use TECWEB\MYAPI\DataBase;

class Read extends DataBase {

    public function __construct() {
        parent::__construct();   // usa root, '', marketzone por defecto
    }

    // LISTAR TODOS LOS PRODUCTOS
    public function list() {
        $sql = "SELECT * FROM productos";
        $result = $this->conexion->query($sql);

        $data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    // OBTENER UN SOLO PRODUCTO POR ID
    public function single($id) {
        $id = intval($id);

        $sql = "SELECT * FROM productos WHERE id = $id";
        $result = $this->conexion->query($sql);

        if ($result && $result->num_rows > 0) {
            // solo un registro
            return $result->fetch_assoc();
        }

        // si no existe, regresamos null o un arreglo vacío
        return null;
    }

    public function search($search) {
    $search = $this->conexion->real_escape_string($search);

    $sql = "SELECT * FROM productos 
            WHERE nombre LIKE '%$search%' 
               OR marca LIKE '%$search%' 
               OR modelo LIKE '%$search%' 
               OR detalles LIKE '%$search%' 
               OR id LIKE '%$search%'";

    $result = $this->conexion->query($sql);

    $data = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}

}
