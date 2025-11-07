<?php
    // Evitar cualquier salida antes del JSON
    error_reporting(0);
    
    include_once __DIR__.'/database.php';

    // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
    $producto = file_get_contents('php://input');
    $data = array(
        'status'  => 'error',
        'message' => 'Error al actualizar el producto'
    );

    if(!empty($producto)) {
        // SE TRANSFORMA EL STRING DEL JSON A OBJETO
        $jsonOBJ = json_decode($producto);
        
        if($jsonOBJ && isset($jsonOBJ->id)) {
            $conexion->set_charset("utf8");
            
            // SE ACTUALIZA EL PRODUCTO
            $sql = "UPDATE productos SET 
                    nombre = '{$jsonOBJ->nombre}',
                    marca = '{$jsonOBJ->marca}',
                    modelo = '{$jsonOBJ->modelo}',
                    precio = {$jsonOBJ->precio},
                    detalles = '{$jsonOBJ->detalles}',
                    unidades = {$jsonOBJ->unidades},
                    imagen = '{$jsonOBJ->imagen}'
                    WHERE id = {$jsonOBJ->id} AND eliminado = 0";
            
            if($conexion->query($sql)){
                $data['status'] = "success";
                $data['message'] = "Producto actualizado correctamente";
            } else {
                $data['message'] = "ERROR: No se ejecutó la actualización. " . mysqli_error($conexion);
            }
        } else {
            $data['message'] = "No se recibió el ID del producto o JSON inválido";
        }
        
        $conexion->close();
    }

    // Asegurar que se devuelva JSON válido
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
?>