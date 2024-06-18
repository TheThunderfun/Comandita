<?php

Class Producto{

    public $id;
    public $sector;
    public $nombre;
    public $precio;
    public $tiempoPreparacion;

    
    public function crearProducto()
        {
            $objDataBase = DB::obtenerInstancia();
            $consulta = $objDataBase->prepararConsulta("INSERT INTO producto 
            (sector, nombre, precio, tiempoPreparacion,) VALUES (:sector, :nombre, :precio, :tiempoPreparacion)");
            
            $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
            $consulta->bindValue(':tiempoPreparacion', $this->tiempoPreparacion, PDO::PARAM_STR);
         

            $consulta->execute();    

        }


        public static function obtenerTodos() {
            try {
                $objDataBase = DB::obtenerInstancia();
                $consulta = $objDataBase->prepararConsulta("SELECT * FROM producto");
                $consulta->execute();
                return $consulta->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error al obtener productos: " . $e->getMessage();
                return []; 
            }
        }
    

}