<?php
header('Content-Type: application/json; charset=utf-8');

// Usar autoload de Composer
require_once __DIR__ . '/../vendor/autoload.php';

use TECWEB\MYAPI\Products;

$productos = new Products('marketzone');
$productos->list();
echo $productos->getData();
?>