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
@$link = new mysqli('localhost', 'root', '', 'marketzone');
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
    <script>
        function modificar(rowId) {
            // Obtener los datos de la fila
            var data = document.getElementById(rowId).querySelectorAll(".row-data");
            
            var id = data[0].innerHTML;
            var nombre = data[1].innerHTML;
            var marca = data[2].innerHTML;
            var modelo = data[3].innerHTML;
            var precio = data[4].innerHTML;
            var unidades = data[5].innerHTML;
            var detalles = data[6].innerHTML;
            var imagen = data[7].querySelector('img').getAttribute('src');
            
            // Enviar los datos por POST al formulario
            send2form(id, nombre, marca, modelo, precio, unidades, detalles, imagen);
        }
        
        function send2form(id, nombre, marca, modelo, precio, unidades, detalles, imagen) {
            var form = document.createElement("form");

            var inputs = [
                {name: 'id', value: id},
                {name: 'nombre', value: nombre},
                {name: 'marca', value: marca},
                {name: 'modelo', value: modelo},
                {name: 'precio', value: precio},
                {name: 'unidades', value: unidades},
                {name: 'detalles', value: detalles},
                {name: 'imagen', value: imagen}
            ];

            inputs.forEach(function(inputData) {
                var input = document.createElement("input");
                input.type = 'hidden';
                input.name = inputData.name;
                input.value = inputData.value;
                form.appendChild(input);
            });

            form.method = 'POST';
            form.action = 'formulario_productos_v2.php';

            document.body.appendChild(form);
            form.submit();
        }
    </script>
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
                    <th>Modificar</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr id="row<?php echo $row['id']; ?>">
                    <th scope="row" class="row-data"><?php echo $row['id']; ?></th>
                    <td class="row-data"><?php echo $row['nombre']; ?></td>
                    <td class="row-data"><?php echo $row['marca']; ?></td>
                    <td class="row-data"><?php echo $row['modelo']; ?></td>
                    <td class="row-data"><?php echo $row['precio']; ?></td>
                    <td class="row-data"><?php echo $row['unidades']; ?></td>
                    <td class="row-data"><?php echo $row['detalles']; ?></td>
                    <td class="row-data"><img src="<?php echo $row['imagen']; ?>" alt="Imagen producto" width="100" /></td>
                    <td>
                        <input type="button" value="Modificar" class="btn btn-primary btn-sm" 
                               onclick="modificar('row<?php echo $row['id']; ?>')" />
                    </td>
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