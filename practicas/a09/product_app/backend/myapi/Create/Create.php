<?php
namespace TECWEB\MYAPI\Create;

use TECWEB\MYAPI\DataBase;

class Create extends DataBase {

    public function __construct() {
        // Usa los valores por defecto de DataBase (root, '', marketzone)
        parent::__construct();
    }

    public function add($jsonOBJ) {

        // Estructura de respuesta por defecto
        $this->data = [
            'status'  => 'error',
            'message' => 'ERROR: No se pudo insertar el producto'
        ];

        // Validar dato mínimo
        if (!isset($jsonOBJ->nombre) || trim($jsonOBJ->nombre) === '') {
            $this->data['message'] = 'ERROR: El nombre es requerido';
            return $this->data;   // 👈 SIEMPRE REGRESAR ALGO
        }

        // Sanitizar / valores por defecto
        $nombre   = $this->conexion->real_escape_string($jsonOBJ->nombre);
        $marca    = $this->conexion->real_escape_string($jsonOBJ->marca   ?? '');
        $modelo   = $this->conexion->real_escape_string($jsonOBJ->modelo  ?? '');
        $precio   = floatval($jsonOBJ->precio   ?? 0);
        $detalles = $this->conexion->real_escape_string($jsonOBJ->detalles ?? '');
        $unidades = intval($jsonOBJ->unidades ?? 0);
        $imagen   = $this->conexion->real_escape_string($jsonOBJ->imagen  ?? 'img/default.png');

        $sql = "
            INSERT INTO productos
                (nombre, marca, modelo, precio, detalles, unidades, imagen)
            VALUES
                ('$nombre', '$marca', '$modelo', $precio, '$detalles', $unidades, '$imagen')
        ";

        if ($this->conexion->query($sql)) {
            $this->data['status']  = 'success';
            $this->data['message'] = 'Producto agregado correctamente';
            $this->data['id']      = $this->conexion->insert_id;
        } else {
            $this->data['message'] = 'ERROR: ' . $this->conexion->error;
        }

        // 👈 MUY IMPORTANTE: devolver el arreglo
        return $this->data;
    }
}
