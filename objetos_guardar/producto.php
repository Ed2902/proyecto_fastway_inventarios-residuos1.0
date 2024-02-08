<?php
    require_once("../clases/producto.php");

    // Obtener el próximo ID disponible consultando la base de datos
    require_once("../clases/conexion.php");
    $conexion = new Conexion();
    $consulta = $conexion->prepare("SELECT MAX(id_producto) AS max_id FROM producto");
    $consulta->execute();
    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    $proximo_id = ($resultado['max_id'] ? $resultado['max_id'] + 1 : 1);

    // Crear el objeto Producto con el próximo ID y los demás datos del formulario
    $objProducto = new Producto($_POST["nombre"], 
                                 $_POST["referencia"], 
                                 $_POST["marca"], 
                                 $_POST["tipo"], 
                                 $_POST["ancho"], 
                                 $_POST["alto"], 
                                 $_POST["profundo"], 
                                 $_POST["id_usuarioFK"],
                                 $proximo_id ); // Pasar el próximo ID como argumento

    // Guardar el producto en la base de datos
    $objProducto->guardar();

    // Puedes imprimir o hacer lo que necesites con los datos del producto guardado
    echo $objProducto->getNombre();
    echo $objProducto->getReferencia();
    echo $objProducto->getMarca();
    echo $objProducto->getTipo();
    echo $objProducto->getAncho();
    echo $objProducto->getAlto();
    echo $objProducto->getProfundo();
    echo $objProducto->getIdUsuarioFK();
?>
