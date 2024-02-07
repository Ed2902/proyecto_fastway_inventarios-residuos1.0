<?php

// Verificar si se recibieron datos mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos como JSON y decodificarlos
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    // Verificar si se pudo decodificar el JSON correctamente
    if ($data !== null) {
            
        error_log('Datos del inventario recibidos:');
        error_log(print_r($data, true));

        // Puedes enviar una respuesta al cliente si es necesario
        $response = ['success' => true, 'message' => 'Datos recibidos correctamente'];
        echo json_encode($response);
    } else {
        // Si hay un error al decodificar el JSON
        $response = ['success' => false, 'message' => 'Error al decodificar los datos JSON'];
        echo json_encode($response);
    }
} else {
    // Si la solicitud no es de tipo POST
    $response = ['success' => false, 'message' => 'Solicitud no válida'];
    echo json_encode($response);
}


?>