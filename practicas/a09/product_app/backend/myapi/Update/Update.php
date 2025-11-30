<?php
namespace TECWEB\MYAPI\Update;

use TECWEB\MYAPI\DataBase;

class Update extends DataBase {

    protected $data;

    public function __construct($db = 'marketzone', $user = 'root', $pass = '') {
        parent::__construct($user, $pass, $db);

        // Estructura por defecto de la respuesta
        $this->data = [
            'status'  => 'error',
            'message' => 'ERROR: No se pudo actualizar el producto'
        ];
    }

    public function edit($jsonOBJ) {
        // Validar que venga el id
        if (!isset($jsonOBJ->id)) {
            $this->data['message'] = 'ERROR: ID de producto no proporcionado';
            return $this->data;
        }

        $id       = (int) $jsonOBJ->id;
        $nombre   = $this->conexion->real_escape_string($jsonOBJ->nombre   ?? '');
        $marca    = $this->conexion->real_escape_string($jsonOBJ->marca    ?? '');
        $modelo   = $this->conexion->real_escape_string($jsonOBJ->modelo   ?? '');
        $precio   = (float)($jsonOBJ->precio   ?? 0);
        $detalles = $this->conexion->real_escape_string($jsonOBJ->detalles ?? '');
        $unidades = (int)($jsonOBJ->unidades ?? 0);
        $imagen   = $this->conexion->real_escape_string($jsonOBJ->imagen   ?? '');

        $sql = "UPDATE productos SET
                    nombre   = '$nombre',
                    marca    = '$marca',
                    modelo   = '$modelo',
                    precio   = $precio,
                    detalles = '$detalles',
                    unidades = $unidades,
                    imagen   = '$imagen'
                WHERE id = $id";

        if ($this->conexion->query($sql)) {
            if ($this->conexion->affected_rows > 0) {
                $this->data['status']  = 'success';
                $this->data['message'] = 'Producto actualizado correctamente';
            } else {
                // No hubo cambios o no existe el id
                $this->data['message'] = 'No se encontró el producto o los datos son iguales';
            }
        } else {
            $this->data['message'] = 'ERROR SQL: ' . $this->conexion->error;
        }

        // MUY IMPORTANTE: SIEMPRE regresar el arreglo
        return $this->data;
    }
}
