<?php
header("Content-Type: application/xhtml+xml; charset=UTF-8");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
<head>
  <title>Resultado de Inserción</title>
</head>
<body>
<?php
// Conexión a la BD
@$link = new mysqli('localhost', 'root', '', 'marketzone');

if ($link->connect_errno) {
    die('<h3>❌ Falló la conexión: ' . $link->connect_error . '</h3>');
}

// Recuperar datos del formulario
$nombre   = $_POST['nombre'];
$marca    = $_POST['marca'];
$modelo   = $_POST['modelo'];
$precio   = $_POST['precio'];
$detalles = $_POST['detalles'];
$unidades = $_POST['unidades'];
$imagen   = $_POST['imagen'];

// Validar si el producto ya existe
$sql_check = "SELECT * FROM productos WHERE nombre='$nombre' AND marca='$marca' AND modelo='$modelo'";
$result = $link->query($sql_check);

if ($result && $result->num_rows > 0) {
    echo '<h2>⚠️ Error: El producto ya existe en la base de datos.</h2>';
} else {
    // --- Query anterior (comentada) ---
    // $sql_insert = "INSERT INTO productos 
    //     (id, nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado)
    //     VALUES (NULL, '$nombre', '$marca', '$modelo', $precio, '$detalles', $unidades, '$imagen', 0)";

    // --- Nueva query usando sólo los nombres de columna necesarios ---
    // Nota: 'id' se genera automáticamente (AUTO_INCREMENT)
    // y 'eliminado' usa el valor por defecto definido en la tabla (0)
    $sql_insert = "INSERT INTO productos 
        (nombre, marca, modelo, precio, detalles, unidades, imagen)
        VALUES ('$nombre', '$marca', '$modelo', $precio, '$detalles', $unidades, '$imagen')";

    if ($link->query($sql_insert)) {
        echo '<h2>✅ Producto insertado correctamente</h2>';
        echo '<ul>';
        echo "<li><strong>Nombre:</strong> $nombre</li>";
        echo "<li><strong>Marca:</strong> $marca</li>";
        echo "<li><strong>Modelo:</strong> $modelo</li>";
        echo "<li><strong>Precio:</strong> $precio</li>";
        echo "<li><strong>Detalles:</strong> $detalles</li>";
        echo "<li><strong>Unidades:</strong> $unidades</li>";
        echo "<li><strong>Imagen:</strong> $imagen</li>";
        echo '</ul>';
    } else {
        echo '<h2>❌ Error al insertar el producto:</h2>';
        echo '<p>' . $link->error . '</p>';
    }
}

$link->close();
?>
</body>
</html>
