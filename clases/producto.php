<?php

class Producto {
    private $codigo_producto;
    private $referencia;
    private $tipo;
    private $marca;
    private $ID_Usuario;
    private $fw;
    private $Cantidad;
    private $conexion;

    public function __construct($codigo_producto, $referencia, $tipo, $marca, $ID_Usuario, $fw, $Cantidad) {
        $this->codigo_producto = $codigo_producto;
        $this->referencia = $referencia;
        $this->tipo = $tipo;
        $this->marca = $marca;
        $this->ID_Usuario = $ID_Usuario;
        $this->fw = $fw;
        $this->Cantidad = $Cantidad;}

                // Getters
                public function getcodigo_producto() {
                    return $this->codigo_producto;
                }

                public function getReferencia() {
                    return $this->referencia;
                }

                public function getTipo() {
                    return $this->tipo;
                }

                public function getMarca() {
                    return $this->marca;
                }

                public function getIDUsuario() {
                    return $this->ID_Usuario;
                }

                public function getFW() {
                    return $this->fw;
                }

                public function getCantidad() {
                    return $this->Cantidad;
                }

                // Setters
                public function setCodigoProducto($codigo_producto) {
                    $this->codigo_producto = $codigo_producto;
                }

                public function setReferencia($referencia) {
                    $this->referencia = $referencia;
                }

                public function setTipo($tipo) {
                    $this->tipo = $tipo;
                }

                public function setMarca($marca) {
                    $this->marca = $marca;
                }

                public function setIDUsuario($ID_Usuario) {
                    $this->ID_Usuario = $ID_Usuario;
                }

                public function setFW($fw) {
                    $this->fw = $fw;
                }

                public function setCantidad($Cantidad) {
                    $this->Cantidad = $Cantidad;
                }

    public function guardar() {
        // Preparar la consulta SQL con marcadores de posición (?)
        $stmt = $this->conexion->prepare('INSERT INTO inventario (codigo_producto, referencia, tipo, marca, ID_Usuario, fw, Cantidad) VALUES (?, ?, ?, ?, ?, ?, ?)');
    
        // Vincular los parámetros
        $stmt->bind_param('ssssssi', $this->codigo_producto, $this->referencia, $this->tipo, $this->marca, $this->ID_Usuario, $this->fw, $this->Cantidad);
    
        // Ejecutar la consulta
        $stmt->execute();
    
        // Verificar si se insertó correctamente
        if ($stmt->affected_rows > 0) {
            echo 'Producto agregado exitosamente';
        } else {
            echo 'Error al agregar el producto';
        }
    
        // Cerrar la consulta
        $stmt->close();
    
        // Cerrar la conexión
        $this->conexion->close();
    }
 }
