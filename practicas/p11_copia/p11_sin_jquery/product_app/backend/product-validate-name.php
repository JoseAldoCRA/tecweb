<?php
    include_once __DIR__.'/database.php';

    // SE OBTIENE LA INFORMACIÓN ENVIADA POR EL CLIENTE
    $input = file_get_contents('php://input');
    $data = array(
        'exists' => false
    );

    if(!empty($input)) {
        $jsonOBJ = json_decode($input);
        
        $nombre = $jsonOBJ->nombre;
        $id = isset($jsonOBJ->id) ? $jsonOBJ->id : null;
        
        // Verificar si el nombre ya existe (excluyendo el producto actual si es edición)
        if($id) {
            // Si es edición, excluir el producto actual de la búsqueda
            $sql = "SELECT * FROM productos WHERE nombre = '{$nombre}' AND eliminado = 0 AND id != {$id}";
        } else {
            // Si es nuevo producto
            $sql = "SELECT * FROM productos WHERE nombre = '{$nombre}' AND eliminado = 0";
        }
        
        $result = $conexion->query($sql);
        
        if($result->num_rows > 0) {
            $data['exists'] = true;
        }
        
        $result->free();
        $conexion->close();
    }

    // SE DEVUELVE LA RESPUESTA EN FORMATO JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>