<?php

require_once("conexion.php");

class cliente {
    private $id_cliente;
    private $nombre;
    private $representantelegal;
    private $telefono;
    private $direccion;
    private $fecha_ingreso;
    private $ruta;

    const TABLA = 'cliente';

    // Constructor
    public function __construct($id_cliente, $nombre, $representantelegal, $telefono, $direccion, $fecha_ingreso, $ruta) {
        $this->id_cliente = $id_cliente;
        $this->nombre = $nombre;
        $this->representantelegal = $representantelegal;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->fecha_ingreso = $fecha_ingreso;
        $this->ruta = $ruta;
    }

    // Getters
    public function getIdCliente() {
        return $this->id_cliente;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getRepresentanteLegal() {
        return $this->representantelegal;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function getFechaIngreso() {
        return $this->fecha_ingreso;
    }

    public function getRuta() {
        return $this->ruta;
    }

    // Setters
    public function setIdCliente($id_cliente) {
        $this->id_cliente = $id_cliente;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setRepresentanteLegal($representantelegal) {
        $this->representantelegal = $representantelegal;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function setFechaIngreso($fecha_ingreso) {
        $this->fecha_ingreso = $fecha_ingreso;
    }

    public function setRuta($ruta) {
        $this->ruta = $ruta;
    }

    public function guardar() {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('INSERT INTO ' .self::TABLA .'(ID_Cliente, NombreCli, RepresentanteLegal, Telefono, DireccionCli, FechaIngreso, Ruta) VALUES (:ID_Cliente, :NombreCli, :RepresentanteLegal, :Telefono, :DireccionCli, :FechaIngreso, :Ruta)');
        $consulta->bindParam(':ID_Cliente', $this->id_cliente);
        $consulta->bindParam(':NombreCli', $this->nombre);
        $consulta->bindParam(':RepresentanteLegal', $this->representantelegal);
        $consulta->bindParam(':Telefono', $this->telefono);
        $consulta->bindParam(':DireccionCli', $this->direccion);
        $consulta->bindParam(':FechaIngreso', $this->fecha_ingreso);
        $consulta->bindParam(':Ruta', $this->ruta);
        $consulta->execute();
    }
}