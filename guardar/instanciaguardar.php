<?php

require_once("../clases/producto.php");

$objProducto = new Producto($_POST["codigo_producto"], $_POST["referencia"], $_POST["tipo"], $_POST["marca"], $_POST["ID_Usuario"], $_POST["fw"], $_POST["Cantidad"]);

$objProducto->guardar();

echo $objProducto->getcodigo_producto();
echo $objProducto->getreferencia();
echo $objProducto->gettipo();    
echo $objProducto->getmarca();
echo $objProducto->getIDUsuario();
echo $objProducto->getfw();
echo $objProducto->getCantidad();
?>