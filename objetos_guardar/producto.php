<?php

    require_once("../clases/producto.php");

    $objProducto = new Producto($_POST["nombre"], 
    $_POST["referencia"], 
    $_POST["marca"], 
    $_POST["tipo"], 
    $_POST["ancho"], 
    $_POST["alto"], 
    $_POST["profundo"], 
    $_POST["id_usuarioFK"] );

    $objProducto->guardar();

    echo $objProducto->getNombre();
    echo $objProducto->getReferencia();
    echo $objProducto->getMarca();
    echo $objProducto->getTipo();
    echo $objProducto->getAncho();
    echo $objProducto->getAlto();
    echo $objProducto->getProfundo();
    echo $objProducto->getIdUsuarioFK();

    

?>