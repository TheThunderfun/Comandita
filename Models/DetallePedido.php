<?php

class DetallePedido {
    public $id;
    public $pedido_id;
    public $producto_id;
    public $cantidad;

    public function __construct($pedido_id, $producto_id, $cantidad) {
        $this->pedido_id = $pedido_id;
        $this->producto_id = $producto_id;
        $this->cantidad = $cantidad;
    }

    public function guardarDetalle($objDataBase) {
        try {
            // Consulta para insertar el detalle del pedido
            $consulta = $objDataBase->prepararConsulta("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad) 
                                                        VALUES (:pedido_id, :producto_id, :cantidad)");
            $consulta->bindValue(':pedido_id', $this->pedido_id, PDO::PARAM_INT);
            $consulta->bindValue(':producto_id', $this->producto_id, PDO::PARAM_INT);
            $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
            
            $consulta->execute();
            
            return true; // Retorna true si la inserción fue exitosa
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al guardar detalle del pedido: " . $e->getMessage();
            return false; // Retorna false si hubo un error
        }
    }
}
