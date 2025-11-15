<?php
header('Content-Type: application/json; charset=utf-8');

use TECWEB\MYAPI\Products;
require_once __DIR__ . './myapi/Products.php';

$productos = new Products('marketzone');
$jsonOBJ = json_decode(file_get_contents('php://input'));

$data = array('exists' => false);

if(!empty($jsonOBJ) && isset($jsonOBJ->nombre)) {
    $productos->singleByName($jsonOBJ->nombre);
    $resultado = json_decode($productos->getData(), true);
    
    if(!empty($resultado)) {
        if(isset($jsonOBJ->id)) {
            if(isset($resultado['id']) && $resultado['id'] != $jsonOBJ->id) {
                $data['exists'] = true;
            }
        } else {
            $data['exists'] = true;
        }
    }
}

echo json_encode($data, JSON_PRETTY_PRINT);
?>
