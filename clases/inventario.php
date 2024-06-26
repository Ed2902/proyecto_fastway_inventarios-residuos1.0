<?php

require_once("conexion.php");

class Inventario {
    protected $id_inventario;
    protected $peso;
    protected $valorkilo;
    protected $id_proveedorFK;
    protected $id_productoFK;
    protected $id_usuarioFK;
    protected $id_ingresoFK;

    public function __construct($peso, $valorkilo, $id_proveedorFK, $id_productoFK, $id_usuarioFK) {
        $this->peso = $peso;
        $this->valorkilo = $valorkilo;
        $this->id_proveedorFK = $id_proveedorFK;
        $this->id_productoFK = $id_productoFK;
        $this->id_usuarioFK = $id_usuarioFK;
    }

    public function getIdInventario() {
        return $this->id_inventario;
    }

    protected function setIdInventario($id_inventario) {
        // No permitir modificar el id_inventario directamente
        // Este valor debe ser autoincrementable en la base de datos
        $this->id_inventario = $id_inventario;
    }

    public function getPeso() {
        return $this->peso;
    }

    public function setPeso($peso) {
        $this->peso = $peso;
    }

    public function getValorkilo() {
        return $this->valorkilo;
    }

    public function setValorkilo($valorkilo) {
        $this->valorkilo = $valorkilo;
    }

    public function getIdProveedorFK() {
        return $this->id_proveedorFK;
    }

    public function setIdProveedorFK($id_proveedorFK) {
        $this->id_proveedorFK = $id_proveedorFK;
    }

    public function getIdProductoFK() {
        return $this->id_productoFK;
    }

    public function setIdProductoFK($id_productoFK) {
        $this->id_productoFK = $id_productoFK;
    }

    public function getIdIngresoFK() {
        return $this->id_ingresoFK;
    }

    protected function setIdIngresoFK($id_ingresoFK) {
        // No permitir modificar el id_ingresoFK directamente
        // Este valor debe ser asignado automáticamente en el método guardar()
        $this->id_ingresoFK = $id_ingresoFK;
    }

    public function getIdUsuarioFK() {
        return $this->id_usuarioFK;
    }

    public function setIdUsuarioFK($id_usuarioFK) {
        $this->id_usuarioFK = $id_usuarioFK;
    }

    public function guardar() {
        $conexion = new Conexion();
        
        $fecha = date("Y-m-d");
        $valortotal = $this->peso * $this->valorkilo; 
        
        $consultaIngreso = $conexion->prepare("INSERT INTO ingreso (fecha, valortotal, id_usuarioFK) VALUES (:fecha, :valortotal, :id_usuarioFK)");
        
        try {
            $consultaIngreso->bindParam(':fecha', $fecha);
            $consultaIngreso->bindParam(':valortotal', $valortotal);
            $consultaIngreso->bindParam(':id_usuarioFK', $this->id_usuarioFK);
            $consultaIngreso->execute();
            
            // Obtener el ID del ingreso recién insertado
            $id_ingreso = $conexion->lastInsertId();
            $this->setIdIngresoFK($id_ingreso); // Actualizar id_ingresoFK con el nuevo valor
            
            // Guardar datos de inventario
            $consultaInventario = $conexion->prepare("INSERT INTO inventario (peso, valorkilo, id_proveedorFK, id_productoFK, id_ingresoFK) VALUES (:peso, :valorkilo, :id_proveedorFK, :id_productoFK, :id_ingresoFK)");
            
            $consultaInventario->bindParam(':peso', $this->peso);
            $consultaInventario->bindParam(':valorkilo', $this->valorkilo);
            $consultaInventario->bindParam(':id_proveedorFK', $this->id_proveedorFK);
            $consultaInventario->bindParam(':id_productoFK', $this->id_productoFK);
            $consultaInventario->bindParam(':id_ingresoFK', $id_ingreso); // Utilizar el ID de ingreso recién insertado
            
            $consultaInventario->execute();
            
            // Obtener el ID del inventario recién insertado
            $this->setIdInventario($conexion->lastInsertId());
            
            return true;
        } catch (PDOException $e) {
            // Registrar y manejar el error
            error_log("Error al guardar inventario: " . $e->getMessage(), 3, "error_log.txt");
            echo "Error al guardar inventario: " . $e->getMessage();
            return false;
        }
    }
}
?>

/* 
    public function mostrarEnTabla() {
        $conexion = new Conexion();
        // Ajuste en la consulta para no seleccionar id_inventario y ordenar adecuadamente los campos
        $consulta = $conexion->query("SELECT ing.fecha as fechaingreso, inv.cantidad, ing.id_ingreso as fw, pro.nombre, pro.referencia, pro.tipo, usu.nombre as usuario, ing.id_clienteFK as cliente
                                      FROM inventario inv
                                      INNER JOIN producto pro ON inv.id_productoFK = pro.id_producto
                                      INNER JOIN ingresos ing ON inv.id_ingresoFK = ing.id_ingreso
                                      INNER JOIN usuario usu ON ing.id_usuarioFK = usu.id_usuario");

        if ($consulta->rowCount() > 0) {
            echo "<tbody>";

            while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>E" . $fila['fw'] . "</td>"; // Cambio para mostrar FW de primeras
                echo "<td>" . $fila['fechaingreso'] . "</td>";
                echo "<td>" . $fila['cantidad'] . "</td>";
                echo "<td>" . $fila['nombre'] . "</td>"; // nombre del producto
                echo "<td>" . $fila['referencia'] . "</td>";
                echo "<td>" . $fila['tipo'] . "</td>";
                echo "<td>" . $fila['usuario'] . "</td>"; // nombre del usuario
                echo "<td>" . $fila['cliente'] . "</td>";
                echo "</tr>";
            }

            echo "</tbody>";
        } else {
            echo "<tr><td colspan='8'>No se encontraron datos de inventario.</td></tr>";
        }

        $conexion = null;
    }

    public function mostrarConsolidadoProductos() {
        $conexion = new Conexion();
        $consulta = $conexion->query("SELECT p.id_producto, p.nombre AS nombre_producto, p.referencia, p.tipo, i.id_clienteFK, SUM(i.cantidad) AS total_cantidad FROM inventario i INNER JOIN producto p ON i.id_productoFK = p.id_producto GROUP BY i.id_productoFK, i.id_clienteFK");

        if ($consulta->rowCount() > 0) {
            while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>".$fila['id_producto']."</td>";
                echo "<td>".$fila['nombre_producto']."</td>";
                echo "<td>".$fila['referencia']."</td>";
                echo "<td>".$fila['tipo']."</td>";
                echo "<td>".$fila['id_clienteFK']."</td>";
                echo "<td>".$fila['total_cantidad']."</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No se encontraron datos de inventario.</td></tr>";
        }

        $conexion = null;
    }

    public function calcularEspacioDisponible() {
        try {
            // Crear una nueva instancia de la clase Conexion
            $conexion = new Conexion();
            
            // Consulta SQL para calcular el volumen total ocupado por los productos en el inventario
            $query = "SELECT SUM(p.ancho * p.alto * p.profundo * i.cantidad) AS volumen_total_ocupado 
                      FROM producto p 
                      INNER JOIN inventario i ON p.id_producto = i.id_productoFK";
            
            // Ejecutar la consulta
            $resultado = $conexion->query($query);
            
            if (!$resultado) {
                throw new PDOException("Error al ejecutar la consulta: " . $conexion->errorInfo()[2]);
            }
            
            // Obtener el resultado de la consulta
            $resultado = $resultado->fetch(PDO::FETCH_ASSOC);
            
            // Obtener el volumen total ocupado
            $volumen_total_ocupado = $resultado['volumen_total_ocupado'] ?? 0;
            
            // Asumiendo que el volumen total de la bodega es de 1000 metros cúbicos
            $volumen_bodega = 1000;
            
            // Calcular el espacio disponible restando el volumen total ocupado del volumen total de la bodega
            $espacio_disponible = $volumen_bodega - $volumen_total_ocupado;
            
            return $espacio_disponible;
        } catch (PDOException $e) {
            echo "Error al calcular el espacio disponible: " . $e->getMessage();
            return false;
        }
    }

    public function calcularEspacioCliente() {
        try {
            // Crear una nueva instancia de la clase Conexion
            $conexion = new Conexion();
            
            // Consulta SQL para calcular el espacio ocupado por cada cliente
            $query = "SELECT i.id_clienteFK, SUM(p.alto * p.ancho * p.profundo * i.cantidad) AS espacio_ocupado_cliente
                      FROM inventario i
                      INNER JOIN producto p ON i.id_productoFK = p.id_producto
                      GROUP BY i.id_clienteFK";
            
            // Ejecutar la consulta
            $resultado = $conexion->query($query);
            
            if (!$resultado) {
                throw new PDOException("Error al ejecutar la consulta: " . $conexion->errorInfo()[2]);
            }
            
            // Crear un array para almacenar los resultados
            $espacio_por_cliente = array();
            
            // Recorrer los resultados y almacenarlos en el array
            while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $espacio_por_cliente[$fila['id_clienteFK']] = $fila['espacio_ocupado_cliente'];
            }
    
            return $espacio_por_cliente;
        } catch (PDOException $e) {
            echo "Error al calcular el espacio por cliente: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerTodosLosInventarios() {
        $conexion = new Conexion();
        $consulta = $conexion->query("SELECT * FROM ingresos");
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTodosLosIngresos() {
        $conexion = new Conexion();
        $consulta = $conexion->query("SELECT * FROM ingresos");
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDatosPorIdIngreso($id_ingreso) {
        $conexion = new Conexion();
        $consulta = $conexion->prepare("SELECT i.*, p.nombre AS nombre_producto, p.referencia AS referencia_producto
                                        FROM inventario i
                                        INNER JOIN producto p ON i.id_productoFK = p.id_producto
                                        WHERE i.id_ingresoFK = ?");
        $consulta->bindParam(1, $id_ingreso, PDO::PARAM_INT);
        $consulta->execute();

        if ($consulta->rowCount() > 0) {
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    } */
}
?>
