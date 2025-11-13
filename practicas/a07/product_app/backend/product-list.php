<?php
header('Content-Type: application/json; charset=utf-8');

use TECWEB\MYAPI\Products;
require_once __DIR__ . '/myapi/Products.php';

$productos = new Products('marketzone');
$productos->list();
echo $productos->getData();
?>