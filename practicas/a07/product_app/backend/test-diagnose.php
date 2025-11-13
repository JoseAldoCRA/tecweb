<?php
// El use debe estar al inicio
use TECWEB\MYAPI\Products;

echo "<h1>Test de Diagnóstico</h1>";

echo "<h2>1. PHP funciona</h2>";
echo "✅ PHP está ejecutándose correctamente<br><br>";

echo "<h2>2. Probando conexión directa</h2>";
$conn = @mysqli_connect('localhost', 'root', '', 'marketzone');
if($conn) {
    echo "✅ Conexión a BD exitosa<br><br>";
    mysqli_close($conn);
} else {
    echo "❌ Error de conexión: " . mysqli_connect_error() . "<br><br>";
}

echo "<h2>3. Probando carga de clases</h2>";
try {
    require_once __DIR__ . '/myapi/DataBase.php';
    echo "✅ DataBase.php cargado<br>";
    
    require_once __DIR__ . '/myapi/Products.php';
    echo "✅ Products.php cargado<br><br>";
    
    echo "<h2>4. Probando instanciación</h2>";
    $productos = new Products('marketzone');
    echo "✅ Objeto Products creado<br><br>";
    
    echo "<h2>5. Probando método list()</h2>";
    $productos->list();
    echo "✅ Método list() ejecutado<br><br>";
    
    echo "<h2>6. Resultado JSON</h2>";
    echo "<pre>";
    echo $productos->getData();
    echo "</pre>";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage();
}
?>