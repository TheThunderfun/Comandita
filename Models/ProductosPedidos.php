<?php
include_once '../DataBase/DB.php';
include_once '../Models/Producto.php';
class ProductosPedido {
    public $id;
    public $pedido_id;
    public $producto;
    public $cantidad;
    public $sector;
    public $estado;
    public $tiempoEstimado;
    public $fechaModf;

   

    public function CargarUno() {
            $objDataBase = DB::obtenerInstancia();
            date_default_timezone_set('America/Argentina/Buenos_Aires');

            $consulta = $objDataBase->prepararConsulta("INSERT INTO productos_pedidos (pedido_id, producto, cantidad,sector,estado,tiempoEstimado,fechaModf) 
                                                        VALUES (:pedido_id, :producto, :cantidad,:sector,:estado,:tiempoEstimado,:fechaModf)");
            $consulta->bindValue(':pedido_id', $this->pedido_id, PDO::PARAM_INT);
            $consulta->bindValue(':producto', $this->producto, PDO::PARAM_STR);
            $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
            $consulta->bindValue(':sector', self::ObtenerSectorPorNombre($this->producto), PDO::PARAM_STR);
            $consulta->bindValue(':estado', "en cola", PDO::PARAM_STR);
            $consulta->bindValue(':tiempoEstimado',$this->tiempoEstimado,PDO::PARAM_INT);
            $consulta->bindValue(':fechaModf',date('Y-m-d H:i:s'),PDO::PARAM_STR);

            $consulta->execute();
   
    }

    public static function BuscarProductoPorNombre($nombre){
        $objDataBase = DB::obtenerInstancia();
        //Producto::listarProductos();
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

    public function ObtenerSectorPorNombre($nombreProducto){
        $objDataBase = DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("SELECT sector FROM producto WHERE nombre = :nombre");
        $consulta->bindParam(':nombre', $nombreProducto, PDO::PARAM_STR);
        $consulta->execute();
    
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    
        if ($resultado) {
            return $resultado['sector'];
        } else {
            return null; // Producto no encontrado 
        }
    }

    public static function modificarPedido($idPedido,$tiempoPreparacion,$estado){
        try {
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $fechaModf=date('Y-m-d H:i:s');
            $objDataBase = DB::obtenerInstancia();

            if($estado=="en preparacion"){
                $consulta = $objDataBase->prepararConsulta("UPDATE productos_pedidos SET tiempoEstimado = :tiempoPreparacion , estado = :estado, fechaModf = :fechaModf WHERE id = :idPedido");
                $consulta->bindParam(':tiempoPreparacion', $tiempoPreparacion, PDO::PARAM_INT);
                $consulta->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
                $consulta->bindParam(':estado', $estado, PDO::PARAM_INT);
                $consulta->bindParam(':fechaModf',$fechaModf,PDO::PARAM_STR);
                $consulta->execute();
            }else{
                $consulta = $objDataBase->prepararConsulta("UPDATE productos_pedidos SET estado = :estado WHERE id = :idPedido");
                $consulta->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
                $consulta->bindParam(':estado', $estado, PDO::PARAM_INT);
                $consulta->execute();
            }
            return true; 
        } catch (PDOException $e) {
            error_log("Error al modificar tiempo de preparación del pedido: " . $e->getMessage());
            return false; 
        }
    }

    public static function functionActualizarEstado($idPedido,$estado){
        $objDataBase = DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("UPDATE productos_pedidos SET estado = :estado WHERE id = :idPedido");
        $consulta->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->bindParam(':estado', $estado, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function ListarPorSector($sector){
        $objDataBase = DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("SELECT id, producto, sector, cantidad, tiempoEstimado FROM productos_Pedidos WHERE sector = :sector");
        $consulta->bindParam(':sector', $sector, PDO::PARAM_STR);
        $consulta->execute();
        $pedidos=$consulta->fetchAll(PDO::FETCH_CLASS,'ProductosPedido');
        //var_dump($pedidos);
        foreach ($pedidos as $pedido) {
            echo "ID: " . $pedido->id . "<br>";
            echo "Producto: " . $pedido->producto . "<br>";
            echo "Sector: " . $pedido->sector . "<br>";
            echo "Stock: " . $pedido->cantidad . "<br>";
            echo "Tiempo de preparacion en minutos: " . $pedido->tiempoEstimado . "<br>";
            }
    }

    public static function ListarPorSectorYEstado($sector,$estado){
        $objDataBase = DB::obtenerInstancia();
        if($sector===null){
            $consulta = $objDataBase->prepararConsulta("SELECT id,pedido_id ,producto, sector, cantidad, tiempoEstimado FROM productos_Pedidos WHERE estado= :estado");
            $consulta->bindParam(':estado',$estado, PDO::PARAM_STR);
            $consulta->execute();
        }else{
            $consulta = $objDataBase->prepararConsulta("SELECT id, producto, pedido_id,sector, cantidad, tiempoEstimado FROM productos_Pedidos WHERE sector = :sector AND estado= :estado");
            $consulta->bindParam(':sector', $sector, PDO::PARAM_STR);
            $consulta->bindParam(':estado',$estado, PDO::PARAM_STR);
            $consulta->execute();
        }
        $pedidos=$consulta->fetchAll(PDO::FETCH_CLASS,'ProductosPedido');
        //var_dump($pedidos);
        foreach ($pedidos as $pedido) {
            echo "ID: " . $pedido->id . "<br>";
            echo "ID del pedido: " . $pedido->pedido_id . "<br>";
            echo "Producto: " . $pedido->producto . "<br>";
            echo "Sector: " . $pedido->sector . "<br>";
            echo "Stock: " . $pedido->cantidad . "<br>";
            echo "Tiempo de preparacion en minutos: " . $pedido->tiempoEstimado . "<br>";
            }
    }

    public static function ObtenerSectorPorId($id){
        $objDataBase = DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("SELECT sector FROM productos_pedidos WHERE id = :id");
        $consulta->bindParam(':id', $id, PDO::PARAM_STR);
        $consulta->execute();
    
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    
        if ($resultado) {
            return $resultado['sector'];
        } else {
            return null; // Producto no encontrado 
        }
    }

    public static function listarPedidosListos(){
        $objDataBase = DB::obtenerInstancia();

        try {
            
            $consulta = $objDataBase->prepararConsulta("
                SELECT pedido_id, 'listo para servir' AS estado
                FROM productos_pedidos
                GROUP BY pedido_id
                HAVING COUNT(CASE WHEN estado <> 'listo para servir' THEN 1 END) = 0
            ");
        
            $consulta->execute();
           
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        
            
            return $resultados;
        
        } catch (PDOException $e) {
            // Manejar errores
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }

    public static function VerTiempoEstimadoPedido($idPedido){
        $objDataBase = DB::obtenerInstancia();

        try {
            $objDataBase = DB::obtenerInstancia();
            $consulta = $objDataBase->prepararConsulta("SELECT SUM(tiempoEstimado) AS tiempoTotal FROM productos_pedidos WHERE pedido_id = :pedido_id");
            $consulta->bindParam(':pedido_id', $idPedido, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_OBJ);
            return $resultado->tiempoTotal;
        } catch (PDOException $e) {
            error_log("Error al sumar tiempos: " . $e->getMessage());
            return null;
        }
    }
    public static function ObtenerLoMasVendido() {
        $objDataBase = DB::obtenerInstancia();

        $consulta = $objDataBase->prepararConsulta("SELECT producto, SUM(cantidad) as total_vendido
                                                    FROM productos_pedidos
                                                    GROUP BY producto
                                                    ORDER BY total_vendido DESC
                                                    LIMIT 1");

        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
    public static function ObtenerLoMenosVendido() {
        $objDataBase = DB::obtenerInstancia();

        $consulta = $objDataBase->prepararConsulta("SELECT producto, SUM(cantidad) as total_vendido
                                                    FROM productos_pedidos
                                                    GROUP BY producto
                                                    ORDER BY total_vendido ASC
                                                    LIMIT 1");

        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
    
}
