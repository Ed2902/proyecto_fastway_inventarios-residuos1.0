<?php

    require_once("conexion.php");

    class Producto {
        private $id_producto;
        private $nombre;
        private $referencia;
        private $tipo;
        private $id_usuarioFK;
    
        public function __construct($id_producto, $nombre, $referencia, $tipo, $id_usuarioFK) {
            $this->id_producto = $id_producto;
            $this->nombre = $nombre;
            $this->referencia = $referencia;
            $this->tipo = $tipo;
            $this->id_usuarioFK = $id_usuarioFK;
        }
    
        public function getIdProducto() {
            return $this->id_producto;
        }
    
        public function setIdProducto($id_producto) {
            $this->id_producto = $id_producto;
        }
    
        public function getNombre() {
            return $this->nombre;
        }
    
        public function setNombre($nombre) {
            $this->nombre = $nombre;
        }
    
        public function getReferencia() {
            return $this->referencia;
        }
    
        public function setReferencia($referencia) {
            $this->referencia = $referencia;
        }
    
        public function getTipo() {
            return $this->tipo;
        }
    
        public function setTipo($tipo) {
            $this->tipo = $tipo;
        }
    
        public function getIdUsuarioFK() {
            return $this->id_usuarioFK;
        }
    
        public function setIdUsuarioFK($id_usuarioFK) {
            $this->id_usuarioFK = $id_usuarioFK;
        }
    
        // Método para guardar un nuevo producto en la base de datos
        public function guardar() {
            $conexion = new Conexion();
            
            try {
                $consulta = $conexion->prepare("INSERT INTO producto (id_producto, nombre, referencia, tipo, fecha, id_usuarioFK) VALUES(:id_producto, :nombre, :referencia, :tipo, NOW(), :id_usuarioFK)");
                
                $consulta->bindParam(':id_producto', $this->id_producto);
                $consulta->bindParam(':nombre', $this->nombre);
                $consulta->bindParam(':referencia', $this->referencia);
                $consulta->bindParam(':tipo', $this->tipo);
                $consulta->bindParam(':id_usuarioFK', $this->id_usuarioFK);
                
                $consulta->execute();
                
                echo "Producto guardado con éxito";
                
                return true;
                
            } catch (PDOException $e) {
                echo "Hay un error: " . $e->getMessage();
                return false;
            }
        }
    
    

        public static function obtenerProductos() {
            $conexion = new Conexion();
            $sql = "SELECT producto.id_producto, 
                           producto.nombre, 
                           producto.referencia, 
                           producto.clienteFK, 
                           producto.tipo, 
                           producto.ancho, 
                           producto.alto, 
                           producto.profundo, 
                           usuario.nombre AS nombre_usuario 
                    FROM producto 
                    INNER JOIN usuario ON producto.id_usuarioFK = usuario.id_usuario";
        
            $consulta = $conexion->prepare($sql);
            
            try {
                $consulta->execute();
                $productos = $consulta->fetchAll(PDO::FETCH_OBJ);
                return $productos;
            } catch (PDOException $e) {
                echo "Error al obtener los productos: " . $e->getMessage();
                return null;
            }
        }

        public static function obtenerProductoPorId($idProducto) {
            $conexion = new Conexion();
            $sql = "SELECT * FROM producto WHERE id_producto = :id";
            $consulta = $conexion->prepare($sql);
            
            try {
                $consulta->bindParam(':id', $idProducto);
                $consulta->execute();
                $producto = $consulta->fetch(PDO::FETCH_ASSOC);
                return $producto;
            } catch (PDOException $e) {
                echo "Error al obtener el producto: " . $e->getMessage();
                return null;
            }
        }

        public static function obtenerClientes() {
            $conexion = new Conexion();
            $sql = "SELECT id_cliente, nombre FROM cliente";
            $consulta = $conexion->prepare($sql);
        
            try {
                $consulta->execute();
                $clientes = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $clientes;
            } catch (PDOException $e) {
                echo "Error al obtener los clientes: " . $e->getMessage();
                return null;
            }
        }

        public static function obtenerNombreUsuarioPorId($idUsuario) {
            $conexion = new Conexion();
            $sql = "SELECT nombre FROM usuario WHERE id_usuario = :id";
            $consulta = $conexion->prepare($sql);
        
            try {
                $consulta->bindParam(':id', $idUsuario);
                $consulta->execute();
                $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                return $resultado['nombre'];
            } catch (PDOException $e) {
                echo "Error al obtener el nombre del usuario: " . $e->getMessage();
                return null;
            }
        }

        
    }



    


        

