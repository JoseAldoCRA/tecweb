<?php
    include_once __DIR__.'/database.php';

    // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
    $producto = file_get_contents('php://input');
    $data = array(
        'status'  => 'error',
        'message' => 'No se recibieron datos'
    );

    if(!empty($producto)) {
        // SE TRANSFORMA EL STRING DEL JSON A OBJETO
        $jsonOBJ = json_decode($producto);
        
        // VALIDAR QUE EL PRODUCTO NO EXISTA
        // Se valida por (nombre Y marca) O (marca Y modelo) en productos NO eliminados
        $sql_check = "SELECT * FROM productos WHERE 
                     ((nombre = '{$jsonOBJ->nombre}' AND marca = '{$jsonOBJ->marca}') 
                     OR (marca = '{$jsonOBJ->marca}' AND modelo = '{$jsonOBJ->modelo}')) 
                     AND eliminado = 0";
        
        $result = $conexion->query($sql_check);
        
        if ($result->num_rows > 0) {
            // EL PRODUCTO YA EXISTE
            $data['status'] = 'error';
            $data['message'] = 'El producto ya existe en la base de datos';
        } else {
            // EL PRODUCTO NO EXISTE, PROCEDER CON LA INSERCIÓN
            $sql_insert = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) 
                          VALUES (
                              '{$jsonOBJ->nombre}', 
                              '{$jsonOBJ->marca}', 
                              '{$jsonOBJ->modelo}', 
                              {$jsonOBJ->precio}, 
                              '{$jsonOBJ->detalles}', 
                              {$jsonOBJ->unidades}, 
                              '{$jsonOBJ->imagen}',
                              0
                          )";
            
            if($conexion->query($sql_insert)){
                $data['status'] = 'success';
                $data['message'] = 'Producto agregado exitosamente';
                $data['id'] = $conexion->insert_id;
            } else {
                $data['status'] = 'error';
                $data['message'] = 'Error al insertar producto: ' . mysqli_error($conexion);
            }
        }
        
        $result->free();
        $conexion->close();
    }
    
    // SE DEVUELVE LA RESPUESTA EN FORMATO JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>