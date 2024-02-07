<?php

require_once("../clases/cliente.php");

$objcliente = new cliente(
$_POST["id_cliente"],
$_POST["nombre"],
$_POST["representantelegal"],
$_POST["telefono"],
$_POST["direccion"],
$_POST["fecha_ingreso"],
$_POST["ruta"]);

$objProducto->guardar();

echo $objcliente->getIdCliente();
echo $objcliente->getNombre();
echo $objcliente->getRepresentanteLegal();    
echo $objcliente->getTelefono();
echo $objcliente->getDireccion();
echo $objcliente->getFechaIngreso();
echo $objcliente->getRuta();
?>