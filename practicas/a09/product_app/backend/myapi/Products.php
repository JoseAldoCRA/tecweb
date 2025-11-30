<?php
namespace TECWEB\MYAPI;  

require_once __DIR__ . '/DataBase.php';

class Products extends DataBase {
    private $data;
   
    public function __construct($database, $user = 'root', $pass = '') {
        // Inicializar el atributo data como array vacío
        $this->data = array();
        
        // Llamar al constructor de la clase padre (DataBase)
        parent::__construct($database, $user, $pass);
    }

    /**
     * Lista todos los productos NO eliminados
     * @return void
     */
    public function list() {
        // Realizar la consulta a la BD
        if($result = $this->conexion->query("SELECT * FROM productos WHERE eliminado = 0")) {
            // Obtener todos los resultados
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if(!is_null($rows)) {
                // Mapear los datos al atributo data
                foreach($rows as $num => $row) {
                    foreach($row as $key => $value) {
                        $this->data[$num][$key] = $value;
                    }
                }
            }
            $result->free();
        } else {
            die('Query Error: '.mysqli_error($this->conexion));
        }
    }

    /**
     * Busca productos por término de búsqueda (nombre, marca, detalles o ID)
     * @param string $search Término de búsqueda
     * @return void
     */
    public function search($search) {
        if(!empty($search)) {
            // Escapar el string para prevenir SQL injection
            $search = mysqli_real_escape_string($this->conexion, $search);
            
            $sql = "SELECT * FROM productos 
                    WHERE (id = '{$search}' 
                    OR nombre LIKE '%{$search}%' 
                    OR marca LIKE '%{$search}%' 
                    OR detalles LIKE '%{$search}%') 
                    AND eliminado = 0";
            
            if($result = $this->conexion->query($sql)) {
                $rows = $result->fetch_all(MYSQLI_ASSOC);

                if(!is_null($rows)) {
                    foreach($rows as $num => $row) {
                        foreach($row as $key => $value) {
                            $this->data[$num][$key] = $value;
                        }
                    }
                }
                $result->free();
            } else {
                die('Query Error: '.mysqli_error($this->conexion));
            }
        }
    }

    /**
     * Busca un producto por su ID
     * @param int $id ID del producto
     * @return void
     */
    public function single($id) {
        if(!empty($id)) {
            $id = mysqli_real_escape_string($this->conexion, $id);
            
            if($result = $this->conexion->query("SELECT * FROM productos WHERE id = {$id}")) {
                $row = $result->fetch_assoc();

                if(!is_null($row)) {
                    foreach($row as $key => $value) {
                        $this->data[$key] = $value;
                    }
                }
                $result->free();
            } else {
                die('Query Error: '.mysqli_error($this->conexion));
            }
        }
    }

    /**
     * Busca un producto por su nombre
     * @param string $name Nombre del producto
     * @return void
     */
    public function singleByName($name) {
        if(!empty($name)) {
            $name = mysqli_real_escape_string($this->conexion, $name);
            
            $sql = "SELECT * FROM productos WHERE nombre = '{$name}' AND eliminado = 0";
            
            if($result = $this->conexion->query($sql)) {
                $row = $result->fetch_assoc();

                if(!is_null($row)) {
                    foreach($row as $key => $value) {
                        $this->data[$key] = $value;
                    }
                }
                $result->free();
            } else {
                die('Query Error: '.mysqli_error($this->conexion));
            }
        }
    }

    /**
     * Agrega un nuevo producto a la BD
     * @param object $producto Objeto JSON con los datos del producto
     * @return void
     */
    public function add($producto) {
        // Inicializar respuesta
        $this->data = array(
            'status'  => 'error',
            'message' => 'Ya existe un producto con ese nombre'
        );

        if(!empty($producto)) {
            // Escapar datos para prevenir SQL injection
            $nombre = mysqli_real_escape_string($this->conexion, $producto->nombre);
            $marca = mysqli_real_escape_string($this->conexion, $producto->marca);
            $modelo = mysqli_real_escape_string($this->conexion, $producto->modelo);
            $precio = mysqli_real_escape_string($this->conexion, $producto->precio);
            $detalles = mysqli_real_escape_string($this->conexion, $producto->detalles);
            $unidades = mysqli_real_escape_string($this->conexion, $producto->unidades);
            $imagen = mysqli_real_escape_string($this->conexion, $producto->imagen);

            // Verificar si el producto ya existe
            $sql = "SELECT * FROM productos WHERE nombre = '{$nombre}' AND eliminado = 0";
            $result = $this->conexion->query($sql);
            
            if($result->num_rows == 0) {
                // Insertar el producto
                $sql = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) 
                        VALUES ('{$nombre}', '{$marca}', '{$modelo}', {$precio}, '{$detalles}', {$unidades}, '{$imagen}', 0)";
                
                if($this->conexion->query($sql)) {
                    $this->data['status'] = "success";
                    $this->data['message'] = "Producto agregado correctamente";
                } else {
                    $this->data['message'] = "ERROR: " . mysqli_error($this->conexion);
                }
            }
            $result->free();
        }
    }

    /**
     * Actualiza un producto existente
     * @param object $producto Objeto JSON con los datos del producto (debe incluir id)
     * @return void
     */
    public function edit($producto) {
        $this->data = array(
            'status'  => 'error',
            'message' => 'No se pudo actualizar el producto'
        );

        if(!empty($producto) && isset($producto->id)) {
            // Escapar datos
            $id = mysqli_real_escape_string($this->conexion, $producto->id);
            $nombre = mysqli_real_escape_string($this->conexion, $producto->nombre);
            $marca = mysqli_real_escape_string($this->conexion, $producto->marca);
            $modelo = mysqli_real_escape_string($this->conexion, $producto->modelo);
            $precio = mysqli_real_escape_string($this->conexion, $producto->precio);
            $detalles = mysqli_real_escape_string($this->conexion, $producto->detalles);
            $unidades = mysqli_real_escape_string($this->conexion, $producto->unidades);
            $imagen = mysqli_real_escape_string($this->conexion, $producto->imagen);

            // Actualizar el producto
            $sql = "UPDATE productos SET 
                    nombre = '{$nombre}',
                    marca = '{$marca}',
                    modelo = '{$modelo}',
                    precio = {$precio},
                    detalles = '{$detalles}',
                    unidades = {$unidades},
                    imagen = '{$imagen}'
                    WHERE id = {$id} AND eliminado = 0";
            
            if($this->conexion->query($sql)) {
                $this->data['status'] = "success";
                $this->data['message'] = "Producto actualizado correctamente";
            } else {
                $this->data['message'] = "ERROR: " . mysqli_error($this->conexion);
            }
        }
    }

    /**
     * Elimina (marca como eliminado) un producto
     * @param int $id ID del producto a eliminar
     * @return void
     */
    public function delete($id) {
        $this->data = array(
            'status'  => 'error',
            'message' => 'No se pudo eliminar el producto'
        );

        if(!empty($id)) {
            $id = mysqli_real_escape_string($this->conexion, $id);
            
            $sql = "UPDATE productos SET eliminado = 1 WHERE id = {$id}";
            
            if($this->conexion->query($sql)) {
                $this->data['status'] = "success";
                $this->data['message'] = "Producto eliminado correctamente";
            } else {
                $this->data['message'] = "ERROR: " . mysqli_error($this->conexion);
            }
        }
    }

    /**
     * Obtiene los datos almacenados en formato JSON
     * @return string JSON con los datos
     */
    public function getData() {
        // Convertir el array a JSON y devolverlo
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
?>