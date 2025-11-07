<?php
    // Evitar cualquier salida antes del JSON
    error_reporting(0);
    
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
        
        if($jsonOBJ) {
            // Validar que el nombre no exista
            $sql = "SELECT * FROM productos WHERE nombre = '{$jsonOBJ->nombre}' AND eliminado = 0";
            $result = $conexion->query($sql);
            
            if ($result->num_rows == 0) {
                $conexion->set_charset("utf8");
                
                // Insertar el producto
                $sql = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) 
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
                
                if($conexion->query($sql)){
                    $data['status'] = "success";
                    $data['message'] = "Producto agregado correctamente";
                } else {
                    $data['status'] = "error";
                    $data['message'] = "Error al insertar: " . mysqli_error($conexion);
                }
            } else {
                $data['status'] = "error";
                $data['message'] = "Ya existe un producto con ese nombre";
            }
            
            $result->free();
        } else {
            $data['status'] = "error";
            $data['message'] = "Error al decodificar JSON";
        }
        
        $conexion->close();
    }

    // Asegurar que se devuelva JSON válido
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
?>