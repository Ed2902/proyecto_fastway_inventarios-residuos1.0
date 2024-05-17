<?php
require_once("../clases/conexion.php");
require_once("../clases/producto.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = $_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $referencia = $_POST['referencia'];
    $tipo = $_POST['tipo'];
    $id_usuarioFK = $_POST['id_usuarioFK'];

    // Crear una instancia de la clase Producto
    $producto = new Producto($id_producto, $nombre, $referencia, $tipo, $id_usuarioFK);

    // Guardar el producto en la base de datos
    if ($producto->guardar()) {
        // Redirigir o mostrar un mensaje de éxito
        header("Location: success.php");
        exit;
    } else {
        // Mostrar un mensaje de error
        echo "Hubo un error al guardar el producto.";
    }
} else {
    echo "Método de solicitud no válido.";
}

?>
