<?php
header('Content-Type: application/json; charset=utf-8');

use TECWEB\MYAPI\Products;
require_once __DIR__ . '/myapi/Products.php';

$productos = new Products('marketzone');
$jsonOBJ = json_decode(file_get_contents('php://input'));

if(!empty($jsonOBJ)) {
    $productos->edit($jsonOBJ);
}

echo $productos->getData();
?>