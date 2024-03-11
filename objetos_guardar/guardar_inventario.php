<?php

// Incluir la clase Inventario
require_once("../clases/inventario.php");

// Obtener los datos del inventario del cuerpo de la solicitud
$inventarioData = json_decode(file_get_contents("php://input"), true);

// Verificar si los datos son válidos
if (!$inventarioData || !is_array($inventarioData)) {
    echo json_encode(array("error" => "Los datos de inventario no son válidos."));
    exit; // Detener la ejecución del script
}

// Recorrer los datos del inventario y guardarlos en la base de datos
foreach ($inventarioData as $filaDatos) {
    // Crear un objeto Inventario con los datos de la fila
    $inventario = new Inventario(
        $filaDatos['cantidad'],
        $filaDatos['fw'],
        $filaDatos['id_productoFK'],
        $filaDatos['id_usuarioFK'],
        $filaDatos['id_clienteFK']
    );

    // Intentar guardar el inventario en la base de datos
    $inventarioGuardado = $inventario->guardar();

    // Verificar si el inventario se guardó correctamente
    if ($inventarioGuardado) {
        echo json_encode(array("mensaje" => "Datos de inventario guardados con éxito."));
    } else {
        echo json_encode(array("error" => "Error al guardar el inventario en la base de datos."));
    }
}
