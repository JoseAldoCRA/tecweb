<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<?php
    $data = array();

    /** Crear el objeto de conexión */
    $link = new mysqli('localhost', 'root', '', 'marketzone');


    /** Comprobar la conexión */
    if ($link->connect_errno) {
        die('Falló la conexión: '.$link->connect_error.'<br/>');
    }

    /** Consulta: solo productos que no estén eliminados */
    if ($result = $link->query("SELECT * FROM productos WHERE eliminado = 0")) {

        /** Se extraen las tuplas obtenidas de la consulta */
        $row = $result->fetch_all(MYSQLI_ASSOC);

        /** Se crea un arreglo con la estructura deseada */
        foreach ($row as $num => $registro) {
            foreach ($registro as $key => $value) {
                $data[$num][$key] = utf8_encode($value);
            }
        }

        /** Liberar memoria */
        $result->free();
    }

    $link->close();
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Productos Vigentes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h3>Productos Vigentes</h3>
        <br/>

        <?php if (!empty($row)) : ?>
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Marca</th>
                        <th scope="col">Modelo</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Unidades</th>
                        <th scope="col">Detalles</th>
                        <th scope="col">Imagen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($row as $value) : ?>
                    <tr>
                        <th scope="row"><?= $value['id'] ?></th>
                        <td><?= $value['nombre'] ?></td>
                        <td><?= $value['marca'] ?></td>
                        <td><?= $value['modelo'] ?></td>
                        <td>$<?= $value['precio'] ?></td>
                        <td><?= $value['unidades'] ?></td>
                        <td><?= $value['detalles'] ?></td>
                        <td><img src="<?= $value['imagen'] ?>" width="80" /></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <h4>No hay productos vigentes para mostrar.</h4>
        <?php endif; ?>
    </div>
</body>
</html>
