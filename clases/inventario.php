<?php

require_once("conexion.php");

class Inventario {
    protected $id_inventario;
    protected $fechaingreso;
    protected $cantidad;
    protected $fw;
    protected $id_productoFK ;
    protected $id_usuarioFK ;
    protected $id_clienteFK;
    

    public function __construct($cantidad, $fw, $id_productoFK, $id_usuarioFK, $id_clienteFK, $id_inventario = null) {
        $this->cantidad = $cantidad;
        $this->fw = $fw;
        $this->id_productoFK = $id_productoFK;
        $this->id_usuarioFK = $id_usuarioFK;
        $this->id_clienteFK = $id_clienteFK;
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

    public function getFw() {
        return $this->fw;
    }

    public function setFw($fw) {
        $this->fw = $fw;
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

    public function guardar() {
        $conexion = new Conexion();
        $consulta = $conexion->prepare("INSERT INTO inventario (fechaingreso, cantidad, fw, id_productoFK, id_usuarioFK, id_clienteFK) VALUES (NOW(), :cantidad, :fw, :id_productoFK, :id_usuarioFK, :id_clienteFK)");
    
        try {
            $consulta->bindParam(':cantidad', $this->cantidad);
            $consulta->bindParam(':fw', $this->fw);
            $consulta->bindParam(':id_productoFK', $this->id_productoFK);
            $consulta->bindParam(':id_usuarioFK', $this->id_usuarioFK);
            $consulta->bindParam(':id_clienteFK', $this->id_clienteFK);
            $consulta->execute();
    
            // Obtener el ID del inventario recién insertado
            $this->id_inventario = $conexion->lastInsertId();
    
            // Devolver el ID del inventario
            return $this->id_inventario;
    
        } catch (PDOException $e) {
            echo "Hay un error: " . $e->getMessage();
            return false; // En caso de error, devuelve falso
        }
    }

    public function mostrarEnTabla() {
        $conexion = new Conexion();
        $consulta = $conexion->query("SELECT * FROM inventario");
    
        if ($consulta->rowCount() > 0) {
            echo "<tbody>";
    
            while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>E".$fila['id_inventario']."</td>";
                echo "<td>".$fila['fechaingreso']."</td>";
                echo "<td>".$fila['cantidad']."</td>";
                echo "<td>".$fila['fw']."</td>";
    
                // Obtener el nombre del producto
                $consultaProducto = $conexion->prepare("SELECT nombre, referencia, tipo FROM producto WHERE id_producto = :id_producto");
                $consultaProducto->bindParam(':id_producto', $fila['id_productoFK']);
                $consultaProducto->execute();
                $producto = $consultaProducto->fetch(PDO::FETCH_ASSOC);
                echo "<td>".$producto['nombre']."</td>";
                echo "<td>".$producto['referencia']."</td>";
                echo "<td>".$producto['tipo']."</td>";
    
                // Obtener el nombre del usuario
                $consultaUsuario = $conexion->prepare("SELECT nombre FROM usuario WHERE id_usuario = :id_usuario");
                $consultaUsuario->bindParam(':id_usuario', $fila['id_usuarioFK']);
                $consultaUsuario->execute();
                $usuario = $consultaUsuario->fetch(PDO::FETCH_ASSOC);
                echo "<td>".$usuario['nombre']."</td>";
    
                echo "<td>".$fila['id_clienteFK']."</td>";
                echo "</tr>";
            }
    
            echo "</tbody>";
        } else {
            echo "<tr><td colspan='9'>No se encontraron datos de inventario.</td></tr>";
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
    
    }
    

