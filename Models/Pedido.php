<?php
include_once "DB.php";
Class Pedido{

    public $id;
    public $cliente;
    public $mesa;
    public $productos;
    public $estado="0";//en preparacion=0 1=finalizado
    public $tiempoPreparacion;
    public $idEmpleado;
    public $foto;



    public function __construct($cliente, $mesa, $productos, $tiempoPreparacion) {
        $this->cliente = $cliente;
        $this->mesa = $mesa;
        $this->productos = $productos;
        $this->tiempoPreparacion = $tiempoPreparacion;
    }
    public function CrearPedido(){
        try {

            $objDataBase = DB::obtenerInstancia();
            $consulta = $objDataBase->PrepararConsulta("INSERT INTO pedido (cliente_id, mesa_id, estado, tiempo_preparacion) 
            VALUES (:cliente_id, :mesa_id, :estado, :tiempo_preparacion)");

            $consulta->bindValue(':cliente_id', $this->cliente->getId(), PDO::PARAM_INT);
            $consulta->bindValue(':mesa_id', $this->mesa->getId(), PDO::PARAM_INT);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $consulta->bindValue(':tiempo_preparacion', $this->tiempoPreparacion, PDO::PARAM_INT);
            $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
            $consulta->bindValue(':idEmpleado', $this->idEmpleado, PDO::PARAM_INT);

            $consulta->execute();
            $idPedidoInsertado = $objDataBase->UltimoId();

            foreach ($this->productos as $producto) {
                $consultaDetalle = $objDataBase->PrepararConsulta("INSERT INTO detalle_pedido (pedido_id, producto_id) 
                                                          VALUES (:pedido_id, :producto_id)");
                $consultaDetalle->bindValue(':pedido_id', $idPedidoInsertado, PDO::PARAM_INT);
                $consultaDetalle->bindValue(':producto_id', $producto->id, PDO::PARAM_INT); 
                $consultaDetalle->execute();
            }
    
        }  catch (PDOException $e) {
        echo "Error al crear el pedido: " . $e->getMessage();
    }
    }
}