<?php

require_once("conexion.php");

class Inventario {
    protected $id_inventario;
    protected $fechaingreso;
    protected $cantidad;
    protected $id_productoFK;
    protected $id_usuarioFK;
    protected $id_clienteFK;
    protected $id_ingresoFK;
    private $conexion;

    public function __construct($cantidad, $id_productoFK, $id_inventario = null) {
        $this->cantidad = $cantidad;
        $this->id_productoFK = $id_productoFK;
        $this->id_inventario = $id_inventario;
        
    }

    public function getIdInventario() {
        return $this->id_inventario;
    }

    public function setIdInventario($id_inventario) {
        $this->id_inventario = $id_inventario;
    }

    public function getCantidad() {
        return $this->cantidad;
    }

    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    public function getIdProductoFK() {
        return $this->id_productoFK;
    }

    public function setIdProductoFK($id_productoFK) {
        $this->id_productoFK = $id_productoFK;
    }

    public function getIdUsuarioFK() {
        return $this->id_usuarioFK;
    }

    public function setIdUsuarioFK($id_usuarioFK) {
        $this->id_usuarioFK = $id_usuarioFK;
    }

    public function getIdClienteFK() {
        return $this->id_clienteFK;
    }

    public function setIdClienteFK($id_clienteFK) {
        $this->id_clienteFK = $id_clienteFK;
    }

    public function getIdIngresoFK() {
        return $this->id_ingresoFK;
    }

    public function setIdIngresoFK($id_ingresoFK) {
        $this->id_ingresoFK = $id_ingresoFK;
    }
    public function guardar($id_usuarioFK, $id_clienteFK, $id_ingresoFK) {
        $conexion = new Conexion();
        $consulta = $conexion->prepare("INSERT INTO inventario (cantidad, id_productoFK, id_ingresoFK) VALUES (:cantidad, :id_productoFK, :id_ingresoFK)");
    
        try {
            $consulta->bindParam(':cantidad', $this->cantidad);
            $consulta->bindParam(':id_productoFK', $this->id_productoFK);
            $consulta->bindParam(':id_ingresoFK', $id_ingresoFK); // Asignar el id_ingresoFK proporcionado
            $consulta->execute();
    
            // Obtener el ID del inventario recién insertado
            $this->id_inventario = $conexion->lastInsertId();
    
            // No necesitamos guardar en la tabla ingresos aquí
    
            return true;
        } catch (PDOException $e) {
            echo "Hay un error: " . $e->getMessage();
            return false; // En caso de error, devuelve falso
        }
    }

    public static function guardarIngreso($id_usuarioFK, $id_clienteFK, $id_inventarioFK) {
        $conexion = new Conexion();
        $consultaIngreso = $conexion->prepare("INSERT INTO ingresos (id_inventarioFK, fecha, id_usuarioFK, id_clienteFK) VALUES (:id_inventarioFK, NOW(), :id_usuarioFK, :id_clienteFK)");

        try {
            $consultaIngreso->bindParam(':id_inventarioFK', $id_inventarioFK);
            $consultaIngreso->bindParam(':id_usuarioFK', $id_usuarioFK);
            $consultaIngreso->bindParam(':id_clienteFK', $id_clienteFK);
            $consultaIngreso->execute();

            return true;
        } catch (PDOException $e) {
            echo "Hay un error: " . $e->getMessage();
            return false; // En caso de error, devuelve falso
        }
    }

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

/*     public static function mostrarIngresos() {
        $conexion = new Conexion();
        $consulta = $conexion->query("SELECT * FROM ingresos");
    
        $html = ""; // Variable para almacenar el HTML de la tabla
    
        if ($consulta->rowCount() > 0) {
            $html .= "<table border='1'>";
            $html .= "<thead><tr><th>ID Ingreso</th><th>Fecha</th><th>ID Usuario</th><th>ID Cliente</th></tr></thead>";
            $html .= "<tbody>";
    
            while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                $html .= "<tr>";
                $html .= "<td>" . $fila['id_ingreso'] . "</td>";
                $html .= "<td>" . $fila['fecha'] . "</td>";
                $html .= "<td>" . $fila['id_usuarioFK'] . "</td>";
                $html .= "<td>" . $fila['id_clienteFK'] . "</td>";
                $html .= "</tr>";
            }
    
            $html .= "</tbody>";
            $html .= "</table>";
        } else {
            $html .= "<p>No se encontraron datos de ingresos.</p>";
        }
    
        $conexion = null;
    
        return $html; // Retornar el HTML generado
    }

    public static function mostrarInventarioPorIngresoFK($id_ingresoFK) {
    $conexion = new Conexion();
    $consulta = $conexion->prepare("SELECT * FROM inventario WHERE id_ingresoFK = :id_ingresoFK");
    $consulta->bindParam(':id_ingresoFK', $id_ingresoFK);
    $consulta->execute();

    $htmlTablaInventario = ""; // Variable para almacenar el HTML de la tabla

    if ($consulta->rowCount() > 0) {
        $htmlTablaInventario .= "<h2>Tabla de Inventario para ID de IngresoFK: $id_ingresoFK</h2>";
        $htmlTablaInventario .= "<table border='1'>";
        $htmlTablaInventario .= "<thead><tr><th>ID Inventario</th><th>Cantidad</th><th>ID Producto</th></tr></thead>";
        $htmlTablaInventario .= "<tbody>";

        while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $htmlTablaInventario .= "<tr>";
            $htmlTablaInventario .= "<td>" . $fila['id_inventario'] . "</td>";
            $htmlTablaInventario .= "<td>" . $fila['cantidad'] . "</td>";
            $htmlTablaInventario .= "<td>" . $fila['id_productoFK'] . "</td>";
            $htmlTablaInventario .= "</tr>";
        }

        $htmlTablaInventario .= "</tbody>";
        $htmlTablaInventario .= "</table>";
    } else {
        $htmlTablaInventario .= "<p>No se encontraron datos de inventario para el ID de ingresoFK proporcionado.</p>";
    }

    $conexion = null;

    return $htmlTablaInventario;
} */

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
    }


}