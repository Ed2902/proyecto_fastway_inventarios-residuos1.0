<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Espacio Ocupado por Cliente</title>
    <!-- Incluye las librerías de Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Estilos opcionales para el contenedor del gráfico */
        #chart-container {
            width: 800px;
            margin: 20px auto;
        }
        #espacio-libre {
            background-color: #F74C1B;
            height: 20px;
            width: 100%;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }
        #espacio-libre-barra {
            background-color: #59A1F7;
            height: 100%;
            width: <?php echo round(($espacio_libre / 1000) * 100, 2); ?>%; /* Redondea a 2 decimales */
            position: absolute;
            top: 0;
            left: 0;
            transition: width 0.5s;
        }
        #espacio-libre-numero {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 10px;
            color: black;
        }
        .espacio-libre-contenedor {
            max-width: 60%; /* Cambia el ancho máximo aquí */
            margin: 0 auto; /* Centra horizontalmente */
        }
    </style>
</head>
<body>
    <h1>Dashboard de Espacio Ocupado por Cliente</h1>
    <div id="chart-container">
        <canvas id="espacio-cliente-chart"></canvas>
    </div>
    <h2>Espacio Libre</h2>
    <div class="espacio-libre-contenedor">
        <div id="espacio-libre">
            <div id="espacio-libre-barra"></div>
            <div id="espacio-libre-numero"><?php echo round($espacio_libre, 2); ?> m³ libres</div> <!-- Redondea a 2 decimales -->
        </div>
    </div>

    <?php
    // Incluye la clase Inventario
    require_once '../clases/inventario.php';

    // Crea una instancia de Inventario
    $objInventario = new Inventario(null, null, null, null, null);

    // Llama al método calcularEspacioCliente para obtener los datos
    $datos_por_cliente = $objInventario->calcularEspacioCliente();

    // Calcula el espacio total ocupado y el espacio libre
    $espacio_total_ocupado = array_sum($datos_por_cliente);
    $espacio_libre = 1000 - $espacio_total_ocupado; // 1000 es el espacio total disponible

    // Genera colores aleatorios para las barras
    function generarColorAleatorio() {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

    // Genera colores aleatorios para las barras
    $colores_barras = [];
    foreach ($datos_por_cliente as $cliente => $espacio) {
        $colores_barras[] = generarColorAleatorio();
    }

    // Convierte los datos en formato JSON para pasarlos al script de JavaScript
    $datos_json = json_encode($datos_por_cliente);
    $colores_json = json_encode($colores_barras);
    ?>
    <script>
        // Recibe los datos JSON del PHP
        var datosPorCliente = <?php echo $datos_json; ?>;
        var coloresBarras = <?php echo $colores_json; ?>;

        // Extrae las etiquetas (IDs de cliente) y los datos de espacio ocupado
        var etiquetas = Object.keys(datosPorCliente);
        var datos = Object.values(datosPorCliente);

        // Configura el gráfico
        var ctx = document.getElementById('espacio-cliente-chart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: etiquetas,
                datasets: [{
                    label: 'Espacio Ocupado por Cliente (metros cúbicos)',
                    data: datos,
                    backgroundColor: coloresBarras, // Colores aleatorios para las barras
                    borderColor: coloresBarras, // Borde de las barras
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        // Función para actualizar la barra de espacio libre
        function actualizarEspacioLibre(espacioLibre) {
            var barra = document.getElementById('espacio-libre-barra');
            var porcentaje = <?php echo round(($espacio_libre / 1000) * 100, 2); ?>; // Redondea a 2 decimales
            barra.style.width = porcentaje + '%';
            document.getElementById('espacio-libre-numero').textContent = espacioLibre.toFixed(2) + ' m³ libres'; // Redondea a 2 decimales
        }

        // Simulación de actualización del espacio libre (puedes reemplazar esto con tu lógica real)
        setInterval(function() {
            // Aquí deberías obtener el espacio libre actualizado desde tu backend
            var espacioLibre = <?php echo $espacio_libre; ?>;
            actualizarEspacioLibre(espacioLibre);
        }, 500); // Actualiza cada medio segundo
    </script>
</body>
</html>
