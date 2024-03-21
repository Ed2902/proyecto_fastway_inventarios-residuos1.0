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

// Variables para almacenar los datos combinados
$id_usuarioFK = null;
$id_clienteFK = null;

// Crear un arreglo para almacenar los IDs de inventario insertados
$id_inventarios_insertados = array();

// Crear un objeto Inventario y guardar en la tabla para cada producto
foreach ($inventarioData as $filaDatos) {
    $id_usuarioFK = $filaDatos['id_usuarioFK']; // Se asume que es el mismo para todos los registros
    $id_clienteFK = $filaDatos['id_clienteFK']; // Se asume que es el mismo para todos los registros

    // Crear un objeto Inventario con los datos de la fila
    $inventario = new Inventario(
        $filaDatos['cantidad'],
        $filaDatos['id_productoFK']
    );

    // Intentar guardar el inventario en la base de datos
    $inventarioGuardado = $inventario->guardar($id_usuarioFK, $id_clienteFK, null); // Aún no se tiene el id_ingresoFK

    // Verificar si el inventario se guardó correctamente
    if ($inventarioGuardado) {
        // Agregar el ID de inventario insertado al arreglo
        $id_inventarios_insertados[] = $inventario->getIdInventario();
    } else {
        // Si hay un error al guardar, mostrar un mensaje de error y detener la ejecución
        echo json_encode(array("error" => "Error al guardar el inventario en la base de datos."));
        exit; // Detener la ejecución si hay un error
    }
}

// Guardar en la tabla ingresos una sola vez
$conexion = new Conexion();
$consultaIngreso = $conexion->prepare("INSERT INTO ingresos (fecha, id_usuarioFK, id_clienteFK) VALUES (NOW(), :id_usuarioFK, :id_clienteFK)");
$consultaIngreso->bindParam(':id_usuarioFK', $id_usuarioFK);
$consultaIngreso->bindParam(':id_clienteFK', $id_clienteFK);

// Intentar ejecutar la consulta de ingresos
if ($consultaIngreso->execute()) {
    // Obtener el ID del ingreso recién insertado
    $id_ingreso = $conexion->lastInsertId();

    // Asociar cada ID de inventario insertado con el ID de ingreso
    foreach ($id_inventarios_insertados as $id_inventario) {
        // Actualizar el id_ingresoFK en cada registro de inventario
        $consultaUpdateInventario = $conexion->prepare("UPDATE inventario SET id_ingresoFK = :id_ingreso WHERE id_inventario = :id_inventario");
        $consultaUpdateInventario->bindParam(':id_ingreso', $id_ingreso);
        $consultaUpdateInventario->bindParam(':id_inventario', $id_inventario);
        
        if ($consultaUpdateInventario->execute()) {
            // Si la actualización fue exitosa, continuar
            continue;
        } else {
            // Si hay un error al actualizar, mostrar un mensaje de error y detener la ejecución
            echo json_encode(array("error" => "Error al actualizar el id_ingresoFK en la tabla inventario."));
            exit; // Detener la ejecución si hay un error
        }
    }

    // Si todo se realizó correctamente, mostrar un mensaje de éxito
    echo json_encode(array("mensaje" => "Datos de inventario e ingresos guardados con éxito."));
} else {
    // Si hay un error al guardar en la tabla ingresos, mostrar un mensaje de error
    echo json_encode(array("error" => "Error al guardar en la tabla ingresos."));
}

?>
