<?php
require_once("../clases/producto.php");

// Obtener el próximo ID disponible consultando la base de datos
require_once("../clases/conexion.php");
$conexion = new Conexion();
$consulta = $conexion->prepare("SELECT MAX(id_producto) AS max_id FROM producto");
$consulta->execute();
$resultado = $consulta->fetch(PDO::FETCH_ASSOC);
$proximo_id = ($resultado['max_id'] ? $resultado['max_id'] + 1 : 1);

// Obtener la lista de clientes
$clientes = Producto::obtenerClientes();

// Verificar si se enviaron datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se seleccionó un cliente válido
    if (isset($_POST["clienteFK"]) && $_POST["clienteFK"] !== '') {
        // Crear el objeto Producto con los datos del formulario
        $fecha_actual = date('Y-m-d H:i:s'); // Obtener la fecha y hora actual
        $objProducto = new Producto($_POST["nombre"], 
                                     $_POST["referencia"], 
                                     $_POST["clienteFK"], 
                                     $_POST["tipo"], 
                                     $_POST["ancho"], 
                                     $_POST["alto"], 
                                     $_POST["profundo"], 
                                     $_POST["id_usuarioFK"],
                                     $fecha_actual, // Pasar la fecha y hora actual
                                     $proximo_id ); // Pasar el próximo ID como argumento

        // Guardar el producto en la base de datos
        $objProducto->guardar();

        // Puedes imprimir o hacer lo que necesites con los datos del producto guardado
        echo $objProducto->getNombre();
        echo $objProducto->getReferencia();
        echo $objProducto->getClienteFK();
        echo $objProducto->getTipo();
        echo $objProducto->getAncho();
        echo $objProducto->getAlto();
        echo $objProducto->getProfundo();
        echo $objProducto->getIdUsuarioFK();
    } else {
        echo "Por favor, seleccione un cliente válido.";
    }
}
?>
