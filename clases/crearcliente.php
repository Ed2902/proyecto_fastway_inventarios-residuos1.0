<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventariofast";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("La conexión falló: " . $conn->connect_error);
}

// Recuperar datos del formulario
$nit = $_POST['id_cliente'];
$nombre = $_POST['nombre'];
$representante_legal = $_POST['representantelegal'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$camara_comercio = $_FILES['camara']['name'];
$rut = $_FILES['rut']['name'];
$cc_representante = $_FILES['cc']['name'];
$certificacion_comercial = $_FILES['comercial']['name'];
$certificacion_bancaria = $_FILES['bancaria']['name'];
$circular_170 = $_FILES['circular']['name'];
$acuerdos_seguridad = $_FILES['seguridad']['name'];
$estados_financieros = $_FILES['financieros']['name'];
$autorizacion_tratamiento_datos = $_FILES['autorizacion']['name'];
$visita = $_FILES['visita']['name'];
$antecedentes_judiciales = $_FILES['antecedentes']['name'];
$fecha_registro = $_POST['fecha'];

// Mover archivos subidos a la carpeta deseada en el servidor
move_uploaded_file($_FILES['camara']['tmp_name'], '../guardar/' . $camara_comercio);
move_uploaded_file($_FILES['rut']['tmp_name'], '../guardar/' . $rut);
move_uploaded_file($_FILES['cc']['tmp_name'], '../guardar/' . $cc_representante);
move_uploaded_file($_FILES['comercial']['tmp_name'], '../guardar/' . $certificacion_comercial);
move_uploaded_file($_FILES['bancaria']['tmp_name'], '../guardar/' . $certificacion_bancaria);
move_uploaded_file($_FILES['circular']['tmp_name'], '../guardar/' . $circular_170);
move_uploaded_file($_FILES['seguridad']['tmp_name'], '../guardar/' . $acuerdos_seguridad);
move_uploaded_file($_FILES['financieros']['tmp_name'], '../guardar/' . $estados_financieros);
move_uploaded_file($_FILES['autorizacion']['tmp_name'], '../guardar/' . $autorizacion_tratamiento_datos);
move_uploaded_file($_FILES['visita']['tmp_name'], '../guardar/' . $visita);
move_uploaded_file($_FILES['antecedentes']['tmp_name'], '../guardar/' . $antecedentes_judiciales);

// Insertar datos en la base de datos
$sql = "INSERT INTO cliente (id_cliente, nombre, representantelegal, telefono, direccion, fecha_registro, camara_comercio, rut, cc_representante, certificacion_comercial, certificacion_bancaria, circular_170, acuerdos_seguridad, estados_financieros, autorizacion_tratamiento_datos, visita, antecedentes_judiciales) VALUES ('$nit', '$nombre', '$representante_legal', '$telefono', '$direccion', '$fecha_registro', '$camara_comercio', '$rut', '$cc_representante', '$certificacion_comercial', '$certificacion_bancaria', '$circular_170', '$acuerdos_seguridad', '$estados_financieros', '$autorizacion_tratamiento_datos', '$visita', '$antecedentes_judiciales')";

if ($conn->query($sql) === TRUE) {
    echo "Nuevo registro insertado correctamente";
} else {
    echo "Error al insertar registro: " . $conn->error;
}

// Cerrar conexión
$conn->close();
?>
