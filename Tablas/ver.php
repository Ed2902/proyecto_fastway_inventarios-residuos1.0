<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Clientes y Archivos</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .container {
            max-width: 1200px; /* Establece una anchura máxima para el contenedor */
        }
        .table-responsive {
            overflow-x: auto; /* Permite el desplazamiento horizontal si la tabla es más ancha que el contenedor */
        }
        .table {
            width: 100%; /* Hace que la tabla ocupe el 100% del contenedor */
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mt-4 mb-4">Tabla de Clientes y Archivos</h2>
    <div class="table-responsive">
        <table id="clienteTable" class="table table-striped table-bordered table-sm">
            <thead class="thead-dark">
                <tr>
                    <th>ID Cliente</th>
                    <th>Nombre</th>
                    <th>Representante Legal</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Fecha de Registro</th>
                    <th>Fecha de Vencimiento</th>
                    <th>Archivos</th>
                </tr>
            </thead>
            <tbody>

            <?php
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

            // Consultar la base de datos
            $sql = "SELECT id_cliente, nombre, representantelegal, telefono, direccion, fecha_registro FROM cliente";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row["id_cliente"]."</td>";
                    echo "<td>".$row["nombre"]."</td>";
                    echo "<td>".$row["representantelegal"]."</td>";
                    echo "<td>".$row["telefono"]."</td>";
                    echo "<td>".$row["direccion"]."</td>";
                    echo "<td>".$row["fecha_registro"]."</td>";

                    // Calcular fecha de vencimiento
                    $fechaRegistro = new DateTime($row["fecha_registro"]);
                    $fechaVencimiento = $fechaRegistro->modify('+1 year')->format('Y-m-d');
                    echo "<td>".$fechaVencimiento."</td>";

                    // Mostrar enlace a la carpeta del cliente
                    $clientFolder = "../guardar/" . $row["nombre"] . "/";
                    echo "<td><a href='$clientFolder' target='_blank'><i class='fas fa-folder'></i> Carpeta del Cliente</a></td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>0 resultados</td></tr>";
            }

            // Cerrar conexión
            $conn->close();
            ?>

            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#clienteTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            }
        });
    });
</script>

</body>
</html>
