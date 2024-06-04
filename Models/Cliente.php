<?php
include_once "Persona.php";
Class Cliente extends Persona{
   // public $codigoPedido;
    //public $codigoMesa;


    public function __construct($id, $nombre, $apellido)//, $codigoPedido, $codigoMesa)
    {
        parent::__construct($id, $nombre, $apellido);
       // $this->codigoPedido = $codigoPedido;
        //$this->codigoMesa = $codigoMesa;
    }

    public function CrearCliente(){
        
        $objDataBase=DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("INSERT INTO clientes (id,nombre, apellido) VALUES (:id,:nombre, :apellido)");

        $consulta->bindValue(':id', $this->id, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        
        $consulta->execute();
    }


}