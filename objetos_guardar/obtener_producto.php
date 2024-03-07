<?php
require_once("../clases/conexion.php");
require_once("../clases/producto.php");

if (isset($_GET['id'])) {
    $idProducto = $_GET['id'];
    $producto = Producto::obtenerProductoPorId($idProducto);

    if ($producto) {
        // Obtener el nombre del cliente asociado al clienteFK
        $nombreCliente = obtenerNombreCliente($producto['clienteFK']);
        if ($nombreCliente) {
            $producto['nombreCliente'] = $nombreCliente;
        } else {
            $producto['nombreCliente'] = 'Cliente no encontrado';
        }

        echo json_encode(array('success' => true, 'producto' => $producto));
    } else {
        echo json_encode(array('success' => false, 'message' => 'No se encontró el producto con el ID proporcionado.'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'No se proporcionó un ID de producto.'));
}

function obtenerNombreCliente($idCliente)
{
    $conexion = new Conexion();
    $sql = "SELECT nombre FROM cliente WHERE id_cliente = :id";
    $consulta = $conexion->prepare($sql);

    try {
        $consulta->bindParam(':id', $idCliente);
        $consulta->execute();
        $cliente = $consulta->fetch(PDO::FETCH_ASSOC);
        return $cliente['nombre']; // Solo devolvemos el nombre del cliente
    } catch (PDOException $e) {
        echo "Error al obtener el cliente: " . $e->getMessage();
        return null;
    }
}
?>