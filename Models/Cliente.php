<?php
;
Class Cliente {

    public $id;
    public $nombre;

    public function __construct() { }

    public function CrearCliente(){
        
        $objDataBase=DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("INSERT INTO clientes (id,nombre, apellido) VALUES (:id,:nombre, :apellido)");

        $consulta->bindValue(':id', $this->id, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
   
        
        $consulta->execute();
    }


    public static function VerTiempoEstimadoMaximo($codigoPedido, $codigoMesa)
    {
        $objDataBase = DB::obtenerInstancia();

        $consulta = $objDataBase->prepararConsulta("SELECT MAX(tiempoEstimado) AS TiempoEstimadoMaximo 
        FROM pedidoproducto 
        WHERE codigoPedido = :codigoPedido 
        AND codigoMesa = :codigoMesa");

        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':codigoMesa', $codigoMesa, PDO::PARAM_STR);
        $consulta->execute();
        $tiempoEstimadoMaximo = $consulta->fetch(PDO::FETCH_ASSOC)['TiempoEstimadoMaximo'];

        
        return $tiempoEstimadoMaximo;
    }

    public static function obtenerTodosCliente()
    {
        try {
            $objDataBase = DB::obtenerInstancia();
            $consulta = $objDataBase->prepararConsulta("SELECT * FROM clientes");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
  
            echo "Error al obtener clientes: " . $e->getMessage();
            return []; 
        }
    }


}

