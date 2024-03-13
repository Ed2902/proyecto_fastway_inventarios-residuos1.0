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
                echo "<td>".$fila['id_inventario']."</td>";
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
    
    
    
}
