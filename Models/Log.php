<?php

class Log{
    public $id;
    public $dni;
    public $sector;
    public $fecha;
    public $metodo;
    public $url;

    public function CargarUno(){
        $objDataBase = DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("INSERT INTO log (dni,sector, metodo, url) VALUES (:dni,:sector,:metodo, :url)");


        $consulta->bindValue(':dni', $this->dni, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
       // $consulta->bindValue(':fecha', date('Y-m-d'), PDO::PARAM_STR);
        $consulta -> bindParam(':metodo', $this->metodo);
        $consulta -> bindParam(':url', $this->url);

        $consulta->execute();

    }

    
}