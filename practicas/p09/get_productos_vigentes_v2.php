<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<?php
    $data = array();

    /** Crear el objeto de conexión */
    @$link = new mysqli('localhost', 'root', '', 'marketzone');


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
    <script>
        function modificar(rowId) {
            // Obtener los datos de la fila
            var data = document.getElementById(rowId).querySelectorAll(".row-data");
            
            var id = data[0].innerHTML;
            var nombre = data[1].innerHTML;
            var marca = data[2].innerHTML;
            var modelo = data[3].innerHTML;
            var precio = data[4].innerHTML.replace('$', '');
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
                        <th scope="col">Modificar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($row as $value) : ?>
                    <tr id="row<?= $value['id'] ?>">
                        <th scope="row" class="row-data"><?= $value['id'] ?></th>
                        <td class="row-data"><?= $value['nombre'] ?></td>
                        <td class="row-data"><?= $value['marca'] ?></td>
                        <td class="row-data"><?= $value['modelo'] ?></td>
                        <td class="row-data">$<?= $value['precio'] ?></td>
                        <td class="row-data"><?= $value['unidades'] ?></td>
                        <td class="row-data"><?= $value['detalles'] ?></td>
                        <td class="row-data"><img src="<?= $value['imagen'] ?>" width="80" /></td>
                        <td>
                            <input type="button" value="Modificar" class="btn btn-primary btn-sm"
                                   onclick="modificar('row<?= $value['id'] ?>')" />
                        </td>
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