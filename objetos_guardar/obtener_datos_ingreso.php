<?php

require_once '../clases/Inventario.php';

// Obtener el ID de ingreso enviado por AJAX
$id = isset($_POST['id']) ? $_POST['id'] : null;

// Crear una instancia de la clase Inventario
$inventario = new Inventario(null, null);

// Si se proporciona un ID de ingreso, obtener y mostrar los datos de inventario
if ($id) {
    $datos_inventario = $inventario->obtenerDatosPorIdIngreso($id);
    // Crear la salida en formato HTML para los datos de inventario
    if ($datos_inventario) {
        // Comenzar la tabla HTML
        echo "<table border='1'>";
        // Encabezados de la tabla
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID de Inventario</th>";
        echo "<th>Cantidad</th>";
        echo "<th>Nombre de Producto</th>";
        echo "<th>Referencia de Producto</th>";
        // Agrega aquí los demás encabezados que desees mostrar
        echo "</tr>";
        echo "</thead>";
        // Cuerpo de la tabla
        echo "<tbody>";
        // Iterar sobre los datos de inventario
        foreach ($datos_inventario as $dato) {
            echo "<tr>";
            echo "<td>{$dato['id_inventario']}</td>";
            echo "<td>{$dato['cantidad']}</td>";
            echo "<td>{$dato['nombre_producto']}</td>";
            echo "<td>{$dato['referencia_producto']}</td>";
            // Agrega aquí las celdas para los demás campos que desees mostrar
            echo "</tr>";
        }
        echo "</tbody>";
        // Cerrar la tabla HTML
        echo "</table>";
    } else {
        echo "No se encontraron datos para el ID de ingreso proporcionado.";
    }
} else {
    // Si no se proporciona un ID de ingreso, imprimir un mensaje de error
    echo "No se proporcionó ningún ID de ingreso.";
}
?>
