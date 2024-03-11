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
    
}
