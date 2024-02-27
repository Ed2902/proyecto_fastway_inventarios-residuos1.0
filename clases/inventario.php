<?php

    require_once("conexion.php");

    class Producto {
        protected $id_producto;
        protected $nombre;
        protected $referencia;
        protected $marca;
        protected $tipo;
        protected $ancho;
        protected $alto;
        protected $profundo;
        protected $id_usuarioFK;

        public function __construct($nombre, $referencia, $marca, $tipo, $ancho, $alto, $profundo, $id_usuarioFK, $id_producto = null) {
            $this->nombre = $nombre;
            $this->referencia = $referencia;
            $this->marca = $marca;
            $this->tipo = $tipo;
            $this->ancho = $ancho;
            $this->alto = $alto;
            $this->profundo = $profundo;
            $this->id_usuarioFK = $id_usuarioFK;
            $this->id_producto = $id_producto; // Agregar esta línea para aceptar el ID como parámetro opcional
        }
    }      

