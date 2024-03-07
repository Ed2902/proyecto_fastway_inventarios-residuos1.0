<?php

    require_once("conexion.php");

    class Producto {
        protected $id_producto;
        protected $nombre;
        protected $referencia;
        protected $clienteFK;
        protected $tipo;
        protected $ancho;
        protected $alto;
        protected $profundo;
        protected $id_usuarioFK;
        protected $fecha_producto;

        public function __construct($nombre, $referencia, $clienteFK, $tipo, $ancho, $alto, $profundo, $id_usuarioFK, $fecha_producto, $id_producto = null) {
            
            $this->nombre = $nombre;
            $this->referencia = $referencia;
            $this->clienteFK = $clienteFK;
            $this->tipo = $tipo;
            $this->ancho = $ancho;
            $this->alto = $alto;
            $this->profundo = $profundo;
            $this->id_usuarioFK = $id_usuarioFK;
            $this->fecha_producto = $fecha_producto;
            $this->id_producto = $id_producto; // Agregar esta línea para aceptar el ID como parámetro opcional
        }
        

        // Getters y Setters
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

        public function getClienteFK() {
            return $this->clienteFK;
        }

        public function setClienteFK($clienteFK) {
            $this->clienteFK = $clienteFK;
        }

        public function getTipo() {
            return $this->tipo;
        }

        public function setTipo($tipo) {
            $this->tipo = $tipo;
        }

        public function getAncho() {
            return $this->ancho;
        }

        public function setAncho($ancho) {
            $this->ancho = $ancho;
        }

        public function getAlto() {
            return $this->alto;
        }

        public function setAlto($alto) {
            $this->alto = $alto;
        }

        public function getProfundo() {
            return $this->profundo;
        }

        public function setProfundo($profundo) {
            $this->profundo = $profundo;
        }

        public function getFechaProducto() {
            return $this->fecha_producto;
        }
        
        public function setFechaProducto($fecha_producto) {
            $this->fecha_producto = $fecha_producto;
        }

        public function getIdUsuarioFK() {
            return $this->id_usuarioFK;
        }

        public function setIdUsuarioFK($id_usuarioFK) {
            $this->id_usuarioFK = $id_usuarioFK;
        }

        public static function obtenerUltimoIdDisponible() {
            $conexion = new Conexion();
            $consulta = $conexion->prepare('SELECT MAX(ID_Producto) AS ultimo_id FROM producto');
            $consulta->execute();
            $ultimoId = $consulta->fetch(PDO::FETCH_ASSOC)['ultimo_id'];
            return $ultimoId !== null ? $ultimoId + 1 : 1;
        }
        

        public function guardar() {
            $conexion = new Conexion();
            
            try {
                $consulta = $conexion->prepare("INSERT INTO producto (nombre, referencia, clienteFK , tipo, ancho, alto, profundo, id_usuarioFK, fecha_producto) VALUES(:nombre, :referencia, :clienteFK , :tipo, :ancho, :alto, :profundo, :id_usuarioFK, :fecha_producto)");
                
                $consulta->bindParam(':nombre', $this->nombre);
                $consulta->bindParam(':referencia', $this->referencia);
                $consulta->bindParam(':clienteFK', $this->clienteFK);
                $consulta->bindParam(':tipo', $this->tipo);
                $consulta->bindParam(':ancho', $this->ancho);
                $consulta->bindParam(':alto', $this->alto);
                $consulta->bindParam(':profundo', $this->profundo);
                $consulta->bindParam(':id_usuarioFK', $this->id_usuarioFK);
                
                $fecha_actual = date('Y-m-d H:i:s');
                $consulta->bindParam(':fecha_producto', $fecha_actual);
                
                $consulta->execute();
                
                // Obtener el ID del producto recién insertado
                $this->id_producto = $conexion->lastInsertId();
                
                echo "Producto guardado con éxito";
                
                // Devolver el ID del producto
                return $this->id_producto;
                
            } catch (PDOException $e) {
                echo "Hay un error: " . $e->getMessage();
                return false; // En caso de error, devuelve falso
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
    }
        

