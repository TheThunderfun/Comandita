<?php
include_once '../DataBase/DB.php';
Class Cliente {

    public $id;
    public $nombre;
    public $apellido;
    public $dni;

    public function __construct() { }

    public function CargarUno(){
        
        $objDataBase=DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("INSERT INTO cliente (nombre, apellido,dni) VALUES (:nombre, :apellido,:dni)");


        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':dni', $this->dni, PDO::PARAM_STR);

        $consulta->execute();
        return $objDataBase->UltimoId();
    }

    public static function obtenerTodosCliente()
    {
            $objDataBase = DB::obtenerInstancia();
            $consulta = $objDataBase->prepararConsulta("SELECT * FROM cliente");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);

    }


}

