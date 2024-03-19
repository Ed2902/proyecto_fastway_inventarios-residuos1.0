<?php


require_once ("conexion.php");

class Orden_salida {

    protected $id_orden;
    protected $cantidades;
    protected $fechaorden;
    protected $id_usuarioFK;
    protected $id_productoFK;
    protected $id_clienteFK;
    protected $fw;
  
    

    public function __construct($cantidades, $fechaorden, $id_usuarioFK, $id_productoFK, $id_clienteFK, $fw, $id_orden=null){

        $this->cantidades = $cantidades;
        $this->fechaorden = $fechaorden;
        $this->id_usuarioFK = $id_usuarioFK;
        $this->id_productoFK = $id_productoFK;
        $this->id_clienteFK = $id_clienteFK;
        $this->fw = $fw;
        $this->id_orden = $id_orden;

    }
    
     // Getters
     public function getIdOrden() {
        return $this->id_orden;
    }

    public function getCantidades() {
        return $this->cantidades;
    }

    public function getFechaOrden() {
        return $this->fechaorden;
    }

    public function getIdUsuarioFK() {
        return $this->id_usuarioFK;
    }

    public function getIdProductoFK() {
        return $this->id_productoFK;
    }

    public function getIdClienteFK() {
        return $this->id_clienteFK;
    }

    public function getfw() {
        return $this->fw;
    }

    

    // Setters
    public function setIdOrden($id_orden) {
        $this->id_orden = $id_orden;
    }

    public function setCantidades($cantidades) {
        $this->cantidades = $cantidades;
    }

    public function setFechaOrden($fechaorden) {
        $this->fechaorden = $fechaorden;
    }

    public function setIdUsuarioFK($id_usuarioFK) {
        $this->id_usuarioFK = $id_usuarioFK;
    }

    public function setIdProductoFK($id_productoFK) {
        $this->id_productoFK = $id_productoFK;
    }

    public function setIdClienteFK($id_clienteFK) {
        $this->id_clienteFK = $id_clienteFK;
    }

    public function setfw($fw) {
        $this->fw = $fw;
    }


    public function guardar() {
        $conexion = new Conexion();
        $consulta = $conexion->prepare("INSERT INTO ordensalida (cantidades, fechaorden, id_usuarioFK, id_productoFK, id_clienteFK, fw) VALUES (:cantidades, NOW(), :id_usuarioFK, :id_productoFK, :id_clienteFK, :fw)");
        
        try {
            $consulta->bindParam(':cantidades', $this->cantidades);
            $consulta->bindParam(':id_usuarioFK', $this->id_usuarioFK);
            $consulta->bindParam(':id_productoFK', $this->id_productoFK);
            $consulta->bindParam(':id_clienteFK', $this->id_clienteFK);
            $consulta->bindParam(':fw', $this->fw);
    
            $consulta->execute();
        
            $this->id_orden = $conexion->lastInsertId();
        
            return $this->id_orden;
        
        } catch (PDOException $e) {
            echo "Hay un error: " . $e->getMessage();
            return false; // En caso de error, devuelve falso
        }
    }
}