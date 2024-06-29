<?php

class Log{
    public $id;
    public $dni;
   // public $password;
    public $sector;
    public $fecha;
    public $metodo;
    public $url;

    public function CargarUno(){
        $objDataBase = DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("INSERT INTO log (dni,sector,fecha, metodo, url) VALUES (:dni,:sector,:fecha,:metodo, :url)");


        $consulta->bindValue(':dni', $this->dni, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':sector', date('Y-m-d'), PDO::PARAM_STR);
        $consulta -> bindParam(':metodo', $this->metodo);
        $consulta -> bindParam(':url', $this->url);

        $consulta->execute();

    }

    
}