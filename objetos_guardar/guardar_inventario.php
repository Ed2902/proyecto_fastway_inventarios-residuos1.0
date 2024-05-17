<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // El código para manejar la solicitud POST aquí
    $inventarioData = json_decode(file_get_contents("php://input"), true);
    // Resto del código...
} else {
    // Si la solicitud no es POST, devolver un error
    echo json_encode(array("error" => "Solo se permiten solicitudes POST."));
}

// Verificar si se han recibido datos mediante una solicitud POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener el contenido JSON enviado desde el cliente
    $json_data = file_get_contents("php://input");

    // Decodificar el JSON en un array asociativo
    $data = json_decode($json_data, true);

    // Verificar si los datos son válidos
    if ($data && is_array($data) && !empty($data)) {
        // Iterar sobre cada conjunto de datos de inventario recibido
        foreach ($data as $inventory) {
            // Acceder a los valores individuales del conjunto de datos
            $id_productoFK = $inventory['id_productoFK'];
            $id_usuarioFK = $inventory['id_usuarioFK'];
            $cantidad = $inventory['cantidad'];
            $id_clienteFK = $inventory['id_clienteFK'];

            // Aquí puedes realizar las operaciones necesarias con los datos, como guardarlos en una base de datos
            // Por ejemplo, podrías realizar una inserción en una tabla de inventario
            // Recuerda usar consultas preparadas para evitar ataques de inyección SQL
        }

        // Envía una respuesta al cliente indicando que los datos se procesaron correctamente
        echo json_encode(array("mensaje" => "Datos de inventario recibidos y procesados correctamente."));
    } else {
        // Si los datos no son válidos, enviar un mensaje de error al cliente
        echo json_encode(array("error" => "Los datos de inventario recibidos no son válidos."));
    }
} else {
    // Si la solicitud no es POST, enviar un mensaje de error al cliente
    echo json_encode(array("error" => "Solo se permiten solicitudes POST."));
}

?>
