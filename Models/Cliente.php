<?php
;
Class Cliente {

    public $id;
    public $nombre;

    public function CrearCliente(){
        
        $objDataBase=DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("INSERT INTO clientes (id,nombre, apellido) VALUES (:id,:nombre, :apellido)");

        $consulta->bindValue(':id', $this->id, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
   
        
        $consulta->execute();
    }


}