<?php

require_once("conexion.php");

class Ingreso {
    protected $id_ingreso;
    protected $fecha;
    protected $id_usuarioFK;
    protected $id_clienteFK;

    public function __construct($id_usuarioFK, $id_clienteFK, $fecha = null) {
        $this->id_usuarioFK = $id_usuarioFK;
        $this->id_clienteFK = $id_clienteFK;
        $this->fecha = $fecha ?? date('Y-m-d H:i:s');
    }

    public function getIdIngreso() {
        return $this->id_ingreso;
    }

    private function generarIdIngreso() {
        $conexion = new Conexion();
        $consulta = $conexion->query("SELECT MAX(id_ingreso) AS max_id FROM ingresos");
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $nuevoId = $resultado['max_id'] + 1;
        return $nuevoId;
    }

    public function guardar() {
        $conexion = new Conexion();
        $this->id_ingreso = $this->generarIdIngreso();
        $consulta = $conexion->prepare("INSERT INTO ingresos (id_ingreso, fecha, id_usuarioFK, id_clienteFK) VALUES (:id_ingreso, :fecha, :id_usuarioFK, :id_clienteFK)");

        try {
            $consulta->bindParam(':id_ingreso', $this->id_ingreso);
            $consulta->bindParam(':fecha', $this->fecha);
            $consulta->bindParam(':id_usuarioFK', $this->id_usuarioFK);
            $consulta->bindParam(':id_clienteFK', $this->id_clienteFK);
            $consulta->execute();

            return true;
        } catch (PDOException $e) {
            echo "Hay un error: " . $e->getMessage();
            return false;
        }
    }
}

?>
