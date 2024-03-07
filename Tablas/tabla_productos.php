<?php
    require_once("../clases/producto.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <!-- Agregar estilos de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Agregar estilos de DataTables -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- Agregar Font Awesome para los íconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Estilos adicionales -->
    <style>
        body {
            padding-top: 20px; /* Ajuste para el menú fijo */
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        table#tablaProductos {
            width: 100% !important; /* Asegura que la tabla ocupe todo el ancho disponible */
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="width: 90%;">
        <!-- Botón de Casa -->
        <a href="../Home/home.html" style="text-decoration: none;">
            <button type="button" class="btn btn-light mr-2" style="border-radius: 50%; background-color:white;">
                <i class="fas fa-home" style="font-size: 20px; color: #fe5000;"></i>
            </button>
        </a>
        <!-- Título -->
        <h1 class="mt-5 mb-3">Productos</h1>
        <!-- Formulario de Búsqueda -->
        
        <div class="table-responsive">
            <table id="tablaProductos" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID Producto</th>
                        <th>Nombre del Producto</th>
                        <th>Referencia</th>
                        <th>Cliente</th>
                        <th>Tipo</th>
                        <th>Ancho</th>
                        <th>Alto</th>
                        <th>Profundo</th>
                        <th>Nombre del Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Obtener productos utilizando el método obtenerProductos de la clase Producto
                    $productos = Producto::obtenerProductos();

                    // Verificar si se obtuvieron productos
                    if (!empty($productos)) {
                        foreach ($productos as $producto) {
                            echo "<tr>";
                            echo "<td>".$producto->id_producto."</td>";
                            echo "<td>".$producto->nombre."</td>";
                            echo "<td>".$producto->referencia."</td>";
                            echo "<td>".$producto->clienteFK."</td>";
                            echo "<td>".$producto->tipo."</td>";
                            echo "<td>".$producto->ancho."</td>";
                            echo "<td>".$producto->alto."</td>";
                            echo "<td>".$producto->profundo."</td>";
                            echo "<td>".$producto->nombre_usuario."</td>"; // Aquí se muestra el nombre del usuario
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No se encontraron productos.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- Botón de Agregar -->
        <a href="../formularios/producto.php" style="text-decoration: none;">
            <button type="button" class="btn btn-success mt-3">
                <i class="fas fa-plus"></i> Agregar
            </button>
        </a>
    </div>

    <!-- Agregar scripts de DataTables y Bootstrap al final del cuerpo del documento -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tablaProductos').DataTable({
                "searching": true 
            });
        });
    </script>
</body>
</html>
