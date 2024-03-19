<?php

require_once("../clases/orden_Salida.php");

// Obtener el cuerpo de la solicitud
$requestBody = file_get_contents("php://input");

// Decodificar el JSON y manejar errores
$ordenData = json_decode($requestBody, true);

if ($ordenData === null && json_last_error() !== JSON_ERROR_NONE) {
    // Error en la decodificación del JSON
    echo json_encode(array("error" => "Error al decodificar el JSON: " . json_last_error_msg()));
    exit;
}

// Verificar si los datos son válidos
if (!$ordenData || !is_array($ordenData)) {
    echo json_encode(array("error" => "Los datos de orden no son válidos."));
    exit; // Detener la ejecución del script
}

// Procesar los datos recibidos
foreach ($ordenData as $filaDatos) {
    // Crear una instancia de Orden_salida con los datos recibidos
    $orden = new Orden_salida(
        $filaDatos['cantidades'],
        $filaDatos['fechaorden'], // Fecha de la orden
        $filaDatos['id_usuarioFK'], // ID del usuario
        $filaDatos['id_productoFK'], // ID del producto
        $filaDatos['id_clienteFK'], // ID del cliente
        $filaDatos['fw'] // fw
    );

    // Intentar guardar la orden en la base de datos
    $ordenGuardada = $orden->guardar();

    // Verificar si la orden se guardó correctamente
    if ($ordenGuardada) {
        echo json_encode(array("mensaje" => "Orden guardada con éxito."));
    } else {
        echo json_encode(array("error" => "Error al guardar la orden en la base de datos."));
    }
}

?>
