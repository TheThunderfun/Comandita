<?php
include_once '../DataBase/DB.php';
include_once '../Models/Producto.php';
class ProductosPedido {
    public $id;
    public $pedido_id;
    public $producto;
    public $cantidad;

    public function CargarUno() {
            $objDataBase = DB::obtenerInstancia();

            $consulta = $objDataBase->prepararConsulta("INSERT INTO productos_pedidos (pedido_id, producto, cantidad) 
                                                        VALUES (:pedido_id, :producto, :cantidad)");
            $consulta->bindValue(':pedido_id', $this->pedido_id, PDO::PARAM_INT);
            $consulta->bindValue(':producto', $this->producto, PDO::PARAM_INT);
            $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
            
            $consulta->execute();
    }

    public static function BuscarProductoPorNombre($nombre){
        $objDataBase = DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("SELECT * FROM producto WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        $producto=$consulta->fetchAll(PDO::FETCH_CLASS,'Producto');
    if($producto){
        return $producto;
    }else{
        return null;
    }
    }

    public static function SumarTiempoProductos($idPedido){
        $objDataBase = DB::obtenerInstancia();
    
        $consultaProductos = $objDataBase->prepararConsulta("SELECT producto, cantidad FROM productos_Pedidos WHERE id = :idPedido");
        $consultaProductos->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consultaProductos->execute();
        $resultadosProductos = $consultaProductos->fetchAll(PDO::FETCH_OBJ);
        $tiempoTotal = 0;
    
        foreach ($resultadosProductos as $producto) {
               $consultaTiempo = $objDataBase->prepararConsulta("
                SELECT tiempoPreparacion
                FROM producto
                WHERE nombre = :nombreProducto
            ");
            $consultaTiempo->bindValue(':nombreProducto', $producto->producto, PDO::PARAM_STR);
            $consultaTiempo->execute();
            $tiempoProducto = $consultaTiempo->fetch(PDO::FETCH_OBJ);
    

            if ($tiempoProducto) {

                $tiempoTotal += $tiempoProducto->tiempoPreparacion * $producto->cantidad;
            } else {

                error_log("No se encontró el tiempo de preparación para el producto: " . $producto->producto);
            }
        }
        return $tiempoTotal;
    }
}
