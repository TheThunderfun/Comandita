<?php

Class Producto{

    public $id;
    public $sector;
    public $nombre;
    public $precio;
    public $tiempoPreparacion;


    public function crearProducto()
        {
            $estado = 1;
            $objAccesoDatos = DB::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO producto 
            (sector, nombre, precio, tiempoPreparacion,) VALUES (:sector, :nombre, :precio, :tiempoPreparacion)");
            
            $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
            $consulta->bindValue(':tiempoPreparacion', $this->tiempoPreparacion, PDO::PARAM_STR);
         

            $consulta->execute();    

        }

}