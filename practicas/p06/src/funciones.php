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

// Ejercicio 5
function validarEdadSexo($edad, $sexo) {
    if ($sexo === "femenino" && $edad >= 18 && $edad <= 35) {
        return "<h3>Bienvenida, usted está en el rango de edad permitido.</h3>";
    } else {
        return "<h3>Lo sentimos, no cumple con los requisitos.</h3>";
    }
}


// ====================
// Ejercicio 6
function parqueVehicular() {
    return [
        "ABC1234" => [
            "Auto" => ["marca"=>"HONDA","modelo"=>2020,"tipo"=>"camioneta"],
            "Propietario" => ["nombre"=>"Juan Pérez","ciudad"=>"Puebla, Pue.","direccion"=>"Av. Reforma 123"]
        ],
        "XYZ5678" => [
            "Auto" => ["marca"=>"MAZDA","modelo"=>2019,"tipo"=>"sedan"],
            "Propietario" => ["nombre"=>"Ana López","ciudad"=>"CDMX","direccion"=>"Col. Roma 45"]
        ],
        "LMN3456" => [
            "Auto" => ["marca"=>"TOYOTA","modelo"=>2021,"tipo"=>"hachback"],
            "Propietario" => ["nombre"=>"Carlos Gómez","ciudad"=>"Guadalajara, Jal.","direccion"=>"Av. Patria 678"]
        ],
        "QWE9876" => [
            "Auto" => ["marca"=>"NISSAN","modelo"=>2018,"tipo"=>"sedan"],
            "Propietario" => ["nombre"=>"María Fernández","ciudad"=>"Monterrey, NL","direccion"=>"Calle Hidalgo 321"]
        ],
        "JKL2468" => [
            "Auto" => ["marca"=>"FORD","modelo"=>2022,"tipo"=>"camioneta"],
            "Propietario" => ["nombre"=>"Luis Ramírez","ciudad"=>"Cancún, QR","direccion"=>"Zona Hotelera"]
        ],
        "RTY1357" => [
            "Auto" => ["marca"=>"CHEVROLET","modelo"=>2020,"tipo"=>"hachback"],
            "Propietario" => ["nombre"=>"Laura Martínez","ciudad"=>"Mérida, Yuc.","direccion"=>"Centro Histórico"]
        ],
        "POI1122" => [
            "Auto" => ["marca"=>"KIA","modelo"=>2021,"tipo"=>"sedan"],
            "Propietario" => ["nombre"=>"Pedro Hernández","ciudad"=>"León, Gto.","direccion"=>"Blvd. López Mateos"]
        ],
        "ASD3344" => [
            "Auto" => ["marca"=>"HYUNDAI","modelo"=>2019,"tipo"=>"camioneta"],
            "Propietario" => ["nombre"=>"Diana Torres","ciudad"=>"Tijuana, BC","direccion"=>"Col. Centro"]
        ],
        "FGH5566" => [
            "Auto" => ["marca"=>"BMW","modelo"=>2022,"tipo"=>"sedan"],
            "Propietario" => ["nombre"=>"José Castillo","ciudad"=>"Querétaro, Qro.","direccion"=>"Av. Zaragoza 88"]
        ],
        "ZXC7788" => [
            "Auto" => ["marca"=>"AUDI","modelo"=>2021,"tipo"=>"hachback"],
            "Propietario" => ["nombre"=>"Sofía Morales","ciudad"=>"Toluca, Edo. Méx.","direccion"=>"Col. Las Torres"]
        ],
        "BNM9900" => [
            "Auto" => ["marca"=>"MERCEDES","modelo"=>2020,"tipo"=>"camioneta"],
            "Propietario" => ["nombre"=>"Héctor Domínguez","ciudad"=>"Puebla, Pue.","direccion"=>"Av. Juárez 150"]
        ],
        "VBN2233" => [
            "Auto" => ["marca"=>"VOLKSWAGEN","modelo"=>2018,"tipo"=>"sedan"],
            "Propietario" => ["nombre"=>"Fernanda Ruiz","ciudad"=>"San Luis Potosí","direccion"=>"Calle Reforma"]
        ],
        "MKO4455" => [
            "Auto" => ["marca"=>"SEAT","modelo"=>2019,"tipo"=>"hachback"],
            "Propietario" => ["nombre"=>"Ricardo Sánchez","ciudad"=>"Oaxaca, Oax.","direccion"=>"Av. Universidad"]
        ],
        "PLK6677" => [
            "Auto" => ["marca"=>"TESLA","modelo"=>2022,"tipo"=>"sedan"],
            "Propietario" => ["nombre"=>"Valeria Chávez","ciudad"=>"CDMX","direccion"=>"Santa Fe"]
        ],
        "GHJ8899" => [
            "Auto" => ["marca"=>"PEUGEOT","modelo"=>2021,"tipo"=>"camioneta"],
            "Propietario" => ["nombre"=>"Andrés Flores","ciudad"=>"Guadalajara, Jal.","direccion"=>"Av. Chapultepec"]
        ]
    ];
}

// Mostrar un vehículo en tabla
function mostrarVehiculo($matricula, $vehiculo) {
    $html = "<h3>Vehículo con matrícula: $matricula</h3>";
    $html .= "<table border='1' cellpadding='5'>
                <tr><th>Matrícula</th><th>Marca</th><th>Modelo</th><th>Tipo</th>
                <th>Propietario</th><th>Ciudad</th><th>Dirección</th></tr>";
    $html .= "<tr>
                <td>$matricula</td>
                <td>{$vehiculo['Auto']['marca']}</td>
                <td>{$vehiculo['Auto']['modelo']}</td>
                <td>{$vehiculo['Auto']['tipo']}</td>
                <td>{$vehiculo['Propietario']['nombre']}</td>
                <td>{$vehiculo['Propietario']['ciudad']}</td>
                <td>{$vehiculo['Propietario']['direccion']}</td>
              </tr>";
    $html .= "</table>";
    return $html;
}

// Mostrar todos en tabla
function mostrarTodosVehiculos($parque) {
    $html = "<h3>Listado completo de autos</h3>";
    $html .= "<table border='1' cellpadding='5'>
                <tr><th>Matrícula</th><th>Marca</th><th>Modelo</th><th>Tipo</th>
                <th>Propietario</th><th>Ciudad</th><th>Dirección</th></tr>";
    foreach ($parque as $matricula => $vehiculo) {
        $html .= "<tr>
                    <td>$matricula</td>
                    <td>{$vehiculo['Auto']['marca']}</td>
                    <td>{$vehiculo['Auto']['modelo']}</td>
                    <td>{$vehiculo['Auto']['tipo']}</td>
                    <td>{$vehiculo['Propietario']['nombre']}</td>
                    <td>{$vehiculo['Propietario']['ciudad']}</td>
                    <td>{$vehiculo['Propietario']['direccion']}</td>
                  </tr>";
    }
    $html .= "</table>";
    return $html;
}
?>


?>

