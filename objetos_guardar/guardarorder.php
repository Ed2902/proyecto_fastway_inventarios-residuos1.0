<?php

require_once("../clases/orden_Salida.php");

$ordenData = json_decode(file_get_contents("php://input"), true);

// Verificar si los datos son válidos
if (!$ordenData || !is_array($ordenData)) {
    echo json_encode(array("error" => "Los datos de orden no son válidos."));
    exit; // Detener la ejecución del script
}

foreach ($ordenData as $filaDatos) {
    // Crear una instancia de Orden_salida con los datos recibidos
    $orden = new Orden_salida(
        $filaDatos['cantidades'],
        $filaDatos['fechaorden'],
        $filaDatos['id_usuarioFK'],
        $filaDatos['id_productoFK'],
        $filaDatos['id_clienteFK'],
        $filaDatos['fw']
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
