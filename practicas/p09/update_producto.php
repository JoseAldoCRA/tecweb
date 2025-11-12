<?php
/* MySQL Conexión */
@$link = new mysqli('localhost', 'root', '', 'marketzone');

// Chequea conexión
if($link === false){
    die("ERROR: No pudo conectarse con la DB. " . mysqli_connect_error());
}

// Verificar que se recibieron los datos por POST
if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $precio = $_POST['precio'];
    $detalles = $_POST['detalles'];
    $unidades = $_POST['unidades'];
    $imagen = $_POST['imagen'];
    
    // Validar que la imagen tenga un valor, si no, usar el default
    if(empty($imagen)) {
        $imagen = 'img/default.png';
    }
    
    // Se actualiza el producto en la tabla 'productos' donde el 'id' coincida
    $sql = "UPDATE productos SET 
            nombre = '{$nombre}', 
            marca = '{$marca}', 
            modelo = '{$modelo}', 
            precio = {$precio}, 
            detalles = '{$detalles}', 
            unidades = {$unidades}, 
            imagen = '{$imagen}' 
            WHERE id = {$id}";
    
    if(mysqli_query($link, $sql)){
        echo "<h2>Registro actualizado correctamente.</h2>";
        echo "<p><strong>Producto ID:</strong> {$id}</p>";
        echo "<p><strong>Nombre:</strong> {$nombre}</p>";
        echo "<p><strong>Marca:</strong> {$marca}</p>";
        echo "<p><strong>Modelo:</strong> {$modelo}</p>";
        echo "<p><strong>Precio:</strong> \${$precio}</p>";
        echo "<p><strong>Unidades:</strong> {$unidades}</p>";
        echo "<br><br>";
        echo "<h3>Regresar a las tablas de productos:</h3>";
        echo "<p><a href='get_productos_xhtml_v2.php?tope=10' style='text-decoration:none; color:blue;'>→ Ver productos con stock limitado (unidades <= 10)</a></p>";
        echo "<p><a href='get_productos_vigentes_v2.php' style='text-decoration:none; color:blue;'>→ Ver todos los productos vigentes</a></p>";
    } else {
        echo "ERROR: No se ejecutó $sql. " . mysqli_error($link);
    }
} else {
    echo "ERROR: No se recibieron datos por POST.";
}

// Cierra la conexión
mysqli_close($link);
?>