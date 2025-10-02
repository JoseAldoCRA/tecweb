<?php
header("Content-type: application/xhtml+xml; charset=UTF-8");

if (!isset($_GET['tope'])) {
    die('Parámetro "tope" no detectado...');
}

$tope = $_GET['tope'];

if (!is_numeric($tope)) {
    die('El parámetro "tope" debe ser numérico.');
}

/** Conexión a la BD */
@$link = new mysqli('localhost', 'root', 'Dyrco_CRA13', 'marketzone');
if ($link->connect_errno) {
    die('Falló la conexión: '.$link->connect_error.'<br/>');
}

/** Consulta con sentencias preparadas */
$sql = "SELECT * FROM productos WHERE unidades <= ?";
$stmt = $link->prepare($sql);
$stmt->bind_param("i", $tope);
$stmt->execute();
$result = $stmt->get_result();

echo "<?xml version='1.0' encoding='UTF-8'?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <title>Productos</title>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous" />
</head>
<body class="p-3">
    <h3>PRODUCTO</h3>
    <br />
    <?php if ($result->num_rows > 0): ?>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Precio</th>
                    <th>Unidades</th>
                    <th>Detalles</th>
                    <th>Imagen</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <th scope="row"><?php echo $row['id']; ?></th>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['marca']; ?></td>
                    <td><?php echo $row['modelo']; ?></td>
                    <td><?php echo $row['precio']; ?></td>
                    <td><?php echo $row['unidades']; ?></td>
                    <td><?php echo $row['detalles']; ?></td>
                    <td><img src="<?php echo $row['imagen']; ?>" alt="Imagen producto" width="100" /></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No se encontraron productos.</p>
    <?php endif; ?>
</body>
</html>
<?php
$stmt->close();
$link->close();
?>