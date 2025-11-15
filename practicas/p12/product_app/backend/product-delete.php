<?php
header('Content-Type: application/json; charset=utf-8');

use TECWEB\MYAPI\Products;
require_once __DIR__ . '/../vendor/autoload.php';

$productos = new Products('marketzone');

if(isset($_GET['id'])) {
    $productos->delete($_GET['id']);
}

echo $productos->getData();
?>
