<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Práctica 4</title>
    </head>
    <body>
        <h1>Práctica 4 - Manejo de Variables en PHP</h1>
        <h2>Ejercicio 1</h2>
        <p>Determina cuál de las siguientes variables son válidas y explica por qué:</p>
        <p>$_myvar,  $_7var,  myvar,  $myvar,  $var7,  $_element1, $house*5</p>
        <?php
            //AQUI VA MI CÓDIGO PHP
            $_myvar;
            $_7var;
            //myvar;       // Inválida
            $myvar;
            $var7;
            $_element1;
            //$house*5;     // Invalida
            
            echo '<h4>Respuesta:</h4>';   
        
            echo '<ul>';
            echo '<li>$_myvar es válida porque inicia con guión bajo.</li>';
            echo '<li>$_7var es válida porque inicia con guión bajo.</li>';
            echo '<li>myvar es inválida porque no tiene el signo de dolar ($).</li>';
            echo '<li>$myvar es válida porque inicia con una letra.</li>';
            echo '<li>$var7 es válida porque inicia con una letra.</li>';
            echo '<li>$_element1 es válida porque inicia con guión bajo.</li>';
            echo '<li>$house*5 es inválida porque el símbolo * no está permitido.</li>';
            echo '</ul>';
        ?>

        <!-- EJERCICIO 2 -->
        <h2>Ejercicio 2</h2>
        <?php
            echo '<h4>Respuesta:</h4>';   
            $a = "ManejadorSQL";
            $b = 'MySQL';
            $c = &$a;  // <-- Hasta aquí cumples con el inicio
            echo "a) Valores iniciales:<br>";          // <-- Inciso a)
            echo "<li>a = $a, b = $b, c = $c </li><br>";

            $a = "PHP server"; // <-- Inciso b)
            $b = &$a;


            echo "c) Se vuelve a mostrar el contenido de cada uno:<br>";       // <-- Inciso c)
            echo "<li>a = $a, b = $b, c = $c </li>";

            echo "<p><strong>d)Explicación:</strong></p>"; 
            echo "<p><li>Como usamos referencias (&), al reasignar \$a y \$b, ambos apuntan al mismo valor y \$c sigue enlazado a \$a.</li></p>"; 
            // <-- Inciso d)
        ?>
        <!-- EJERCICIO 3 -->
        <h2>Ejercicio 3</h2>
        <?php
            echo "<h4>Respuesta:</h4>"; 
            $a = "PHP5";
            $z[] = &$a;
            $b = "5a version de PHP";
            $c = (int)$b * 10;   // fuerza conversión a entero
            $a .= $b;       // concatena
            $b *= $c;       // multiplica
            $z[0] = "MySQL";
            echo "<pre>";
            var_dump($a,$b,$c,$z);
            echo "</pre>";
            unset($a,$b,$c,$z);
        ?>

    </body>
</html>