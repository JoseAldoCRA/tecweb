<?php
namespace TECWEB\MYAPI\Delete;

use TECWEB\MYAPI\DataBase;

class Delete extends DataBase {

    public function __construct() {
        parent::__construct();
    }

    public function delete($id) {
        // Valor de retorno por defecto
        $data = [
            'status'  => 'error',
            'message' => 'ID no válido'
        ];

        // Validar ID
        if (empty($id) || !is_numeric($id)) {
            return $data;
        }

        $id = (int) $id;

        // Aquí decide tu profe: DELETE físico o borrado lógico
        // Opción A: borrar registro
        // $sql = "DELETE FROM productos WHERE id = $id";

        // Opción B: borrado lógico (si tienes campo eliminado)
        $sql = "UPDATE productos SET eliminado = 1 WHERE id = $id";

        if ($this->conexion->query($sql)) {

            if ($this->conexion->affected_rows > 0) {
                $data['status']  = 'success';
                $data['message'] = 'Producto eliminado correctamente';
            } else {
                $data['status']  = 'warning';
                $data['message'] = 'No se encontró producto con ese ID';
            }

        } else {
            $data['status']  = 'error';
            $data['message'] = 'Error al eliminar: ' . $this->conexion->error;
        }

        return $data;   // 👈 IMPORTANTE
    }
}
