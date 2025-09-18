<?php
// Función que revisa si un número es múltiplo de 5 y 7
function esMultiplo57($num) {
    if ($num % 5 == 0 && $num % 7 == 0) {
        return "R= El número $num SÍ es múltiplo de 5 y 7.";
    } else {
        return "R= El número $num NO es múltiplo de 5 y 7.";
    }
}

// Ejercicio 2: generar secuencia impar-par-impar
function generarSecuencia() {
    $matriz = [];
    $iteraciones = 0;

    // Repetir hasta obtener un trío impar-par-impar
    do {
        $a = rand(100, 999);
        $b = rand(100, 999);
        $c = rand(100, 999);

        $matriz[] = [$a, $b, $c];
        $iteraciones++;
    } while (!($a % 2 != 0 && $b % 2 == 0 && $c % 2 != 0));

    // Mostrar resultados
    $salida = "<h3>Resultados:</h3><table border='1' cellpadding='5'>";
    foreach ($matriz as $fila) {
        $salida .= "<tr><td>{$fila[0]}</td><td>{$fila[1]}</td><td>{$fila[2]}</td></tr>";
    }
    $salida .= "</table>";
    $salida .= "<p>Total: ".($iteraciones*3)." números generados en $iteraciones iteraciones.</p>";

    return $salida;
}

// Ejercicio 3: buscar primer múltiplo con while
function buscarMultiploWhile($m) {
    $num = 0;
    while (true) {
        $num = rand(1, 1000);
        if ($num % $m == 0) {
            return $num;
        }
    }
}

// Ejercicio 3: buscar primer múltiplo con do-while
function buscarMultiploDoWhile($m) {
    do {
        $num = rand(1, 1000);
    } while ($num % $m != 0);
    return $num;
}

// Ejercicio 4: arreglo ASCII [97-122] con foreach
function arregloAscii() {
    $arr = [];
    for ($i = 97; $i <= 122; $i++) {
        $arr[$i] = chr($i);
    }

    $salida = "<h3>Tabla ASCII de letras a-z:</h3>";
    $salida .= "<table border='1' cellpadding='5'>";
    $salida .= "<tr><th>Índice</th><th>Letra</th></tr>";
    foreach ($arr as $key => $value) {
        $salida .= "<tr><td>$key</td><td>$value</td></tr>";
    }
    $salida .= "</table>";

    return $salida;
}
?>

