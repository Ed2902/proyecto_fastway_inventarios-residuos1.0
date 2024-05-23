<?php
// Obtener el contenido JSON enviado desde el cliente
$json_data = file_get_contents("php://input");

// Imprimir el JSON recibido para depuración
error_log("JSON recibido: " . $json_data, 0);

// Decodificar el JSON en un array asociativo
$data = json_decode($json_data, true);

// Verificar si el JSON es válido
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    // Si el JSON no es válido, enviar un mensaje de error y el código de estado 400
    http_response_code(400); // Bad Request
    echo json_encode(array(
        "errors" => array(
            array(
                "error" => "Error: Datos JSON no válidos. Error: " . json_last_error_msg()
            )
        )
    ));
    exit; // Salir del script
}