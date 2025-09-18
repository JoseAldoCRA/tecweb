<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 6</title>
</head>
<body>
    <?php include 'src/funciones.php'; ?>
    <h2>Ejercicio 1</h2>
    <p>Escribir programa para comprobar si un número es un múltiplo de 5 y 7</p>
    <?php
        if (isset($_GET['numero'])) {
            $num = $_GET['numero'];
            echo "<h3>" . esMultiplo57($num) . "</h3>";
        }
    ?>

    <h2>Ejercicio 2</h2>
    <p>Crea un programa para la generación repetitiva de 3 números aleatorios hasta obtener una
    secuencia compuesta por:</p>
    <?php
        echo generarSecuencia();
    ?>

    <h2>Ejercicio 3</h2>
    <p>Buscar el primer número aleatorio múltiplo de un número dado (GET).</p>
    <p>Ejemplo de uso: <code>?m=7</code> en la URL.</p>
    <?php
        if (isset($_GET['m'])) {
            $m = $_GET['m'];
            echo "<p>Con while → " . buscarMultiploWhile($m) . "</p>";
            echo "<p>Con do-while → " . buscarMultiploDoWhile($m) . "</p>";
        }
    ?>

    <h2>Ejercicio 4</h2>
    <p>Arreglo con índices 97–122 y valores de la 'a' a la 'z'.</p>
    <?php
        echo arregloAscii();
    ?>

    <h2>Ejercicio 5</h2>
    <form method="POST">
    <label for="edad">Edad:</label>
    <input type="number" id="edad" name="edad" required><br><br>

    <label for="sexo">Sexo:</label>
    <select id="sexo" name="sexo" required>
        <option value="femenino">Femenino</option>
        <option value="masculino">Masculino</option>
    </select><br><br>

    <input type="submit" value="Enviar">
    </form>

    <?php
        if (isset($_POST['edad']) && isset($_POST['sexo'])) {
            echo validarEdadSexo($_POST['edad'], $_POST['sexo']);
        }
    ?>

    <h2>Ejercicio 6</h2>
    <p>Consulta de parque vehicular</p>
    <form method="POST">
        <label for="matricula">Consultar por matrícula:</label>
        <input type="text" id="matricula" name="matricula" placeholder="Ej: ABC1234"><br><br>

        <button type="submit" name="buscar">Buscar</button>
        <button type="submit" name="todos">Ver todos</button>
    </form>

    <?php
        $parque = parqueVehicular();

        if (isset($_POST['buscar'])) {
            $m = $_POST['matricula'];
            if (isset($parque[$m])) {
                echo "<h3>📌 Salida en tabla</h3>";
                echo mostrarVehiculo($m, $parque[$m]);

                echo "<h3>📌 Salida con print_r (estructura del arreglo)</h3>";
                echo "<pre>"; print_r($parque[$m]); echo "</pre>";
            } else {
                echo "<p><b>No existe la matrícula ingresada.</b></p>";
            }
        }

        if (isset($_POST['todos'])) {
            echo "<h3>📌 Salida en tabla</h3>";
            echo mostrarTodosVehiculos($parque);

            echo "<h3>📌 Salida con print_r (estructura completa del arreglo)</h3>";
            echo "<pre>"; print_r($parque); echo "</pre>";
        }
    ?>


    <h2>Ejemplo de POST</h2>
    <form action="http://localhost/tecweb/practicas/p04/index.php" method="post">
        Name: <input type="text" name="name"><br>
        E-mail: <input type="text" name="email"><br>
        <input type="submit">
    </form>
    <br>
    <?php
        if(isset($_POST["name"]) && isset($_POST["email"]))
        {
            echo $_POST["name"];
            echo '<br>';
            echo $_POST["email"];
        }
    ?>
</body>
</html>