<?php
Class Usuario {

    public $id;
    public $nombre;
    public $tipo;
    public $codigo;
    public $fechaAlta;

    public function crearUsuario()
    {
        $objAccesoDatos = DB::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuario (nombre, tipo,codigo
         fechaAlta)
         VALUES (:nombre, :clave, :tipoUsuario, :codigo, :fechaAlta)");
       
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_INT);
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', $this->fechaAlta, PDO::PARAM_STR);


        $consulta->execute();
    }

}