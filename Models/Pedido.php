<?php
include_once '../DataBase/DB.php';
include_once '../Models/ProductosPedidos.php';
Class Pedido{

    public $id;
    public $cliente;
    public $mesa;
    public $estado="en preparacion";
    public $tiempoPreparacionEstimado;
    public $idEmpleado;
    public $foto;
    

    public function CrearPedido(){
        $objDataBase = DB::obtenerInstancia();
    
        $consulta = $objDataBase->prepararConsulta("INSERT INTO pedido (cliente, mesa, estado, tiempoPreparacionEstimado, idEmpleado) 
            VALUES (:cliente, :mesa, :estado, :tiempoPreparacion, :idEmpleado)");
    
        
        $consulta->bindValue(':cliente', $this->cliente, PDO::PARAM_STR);
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_INT); 
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoPreparacion', 20, PDO::PARAM_INT); 
        $consulta->bindValue(':idEmpleado', $this->idEmpleado, PDO::PARAM_INT);
    
     
        
        $consulta->execute();
        
        if ($this->foto != null) {
            $this->GuardarImagen($this->mesa);
        }
        $idPedido = $objDataBase->UltimoId();
    
        return $idPedido;
    }

    private function GuardarImagen($mesa){
        
           $directorioImagenes = 'ImagenesDeLaMesa/2024/';
        
           if (!file_exists($directorioImagenes)) {
               mkdir($directorioImagenes, 0777, true);
           }
        
           $nombreImagen = $this->cliente .$this->mesa . '_' . date('Ymd_His') . '.jpg';
           $rutaImagen = $directorioImagenes . $nombreImagen;
        
           move_uploaded_file($this->foto->getStream()->getMetadata('uri'), $rutaImagen);
        
           $rutaCompletaImagen = $rutaImagen; 
           $db = DB::obtenerInstancia();
           $stmt = $db->prepararConsulta("UPDATE pedido SET foto = ? WHERE mesa = ?");
           $stmt->execute([$rutaCompletaImagen, $mesa]);
        
        }



        public static function ActulizarTiempoEstimado($tiempoEstimado,$idPedido){
            $objDataBase = DB::obtenerInstancia();
            $consultaEsperando = $objDataBase->prepararConsulta("UPDATE pedido SET tiempoPreparacionEstimado = $tiempoEstimado WHERE id = $idPedido");
            $consultaEsperando->execute();
        }

        public static function PedidoExiste($mesa,$pedido){
            try {
                $objDataBase = DB::obtenerInstancia();
                $consulta = $objDataBase->prepararConsulta("SELECT * FROM pedido WHERE mesa = :codigoMesa AND id = :idPedido");
                $consulta->bindParam(':codigoMesa', $mesa, PDO::PARAM_STR);
                $consulta->bindParam(':idPedido', $pedido, PDO::PARAM_INT);
                $consulta->execute();
                return $consulta->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Error al obtener información: " . $e->getMessage());
                return false;
            }
        }

        public static function ObtenerTodos(){
            $objDataBase = DB::obtenerInstancia();
            $consulta = $objDataBase->prepararConsulta("SELECT * FROM pedido");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS,"Pedido");
        }

        public static function CambiarEstadoMesaYpedido($a, $b) {
            $objDataBase = DB::obtenerInstancia();
    
            try {

                $consultaMesa = $objDataBase->prepararConsulta("UPDATE mesa SET estado = :estado WHERE codigoMesa = :codigoMesa");
                $consultaMesa->bindParam(':codigoMesa', $a, PDO::PARAM_STR);
                $consultaMesa->bindParam(':estado', $b, PDO::PARAM_STR);
                $consultaMesa->execute();
        
                $consultaPedido = $objDataBase->prepararConsulta("UPDATE pedido SET estado = :estado WHERE mesa = :codigoMesa");
                $consultaPedido->bindParam(':codigoMesa', $a, PDO::PARAM_STR);
                $consultaPedido->bindParam(':estado', $b, PDO::PARAM_STR);
                $consultaPedido->execute();
                

            } catch (PDOException $e) {
                echo("Error al actualizar estado de mesa y pedido: " . $e->getMessage());
            }
        }

        public static function CambiarEstadoMesa($mesa,$estado){
            var_dump($mesa);
            var_dump($estado);
            $objDataBase = DB::obtenerInstancia();
            $consultaMesa = $objDataBase->prepararConsulta("UPDATE mesa SET estado = :estado WHERE codigoMesa = :codigoMesa");
            $consultaMesa->bindParam(':codigoMesa', $mesa, PDO::PARAM_STR);
            $consultaMesa->bindParam(':estado', $estado, PDO::PARAM_STR);
            $consultaMesa->execute();
        }
        public static function CalcularValorTotalPorMesa($codigoMesa) {
            $objDataBase = DB::obtenerInstancia();
            
            $consulta = $objDataBase->prepararConsulta(
                "SELECT SUM(pp.cantidad * p.precio) AS valor_total 
                 FROM pedido pe 
                 JOIN productos_pedidos pp ON pe.id = pp.pedido_id 
                 JOIN producto p ON pp.producto = p.nombre 
                 WHERE pe.mesa = :codigoMesa"
            );
            
            $consulta->bindParam(':codigoMesa', $codigoMesa, PDO::PARAM_STR);
            $consulta->execute();
            
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            
            return $resultado['valor_total'] ? $resultado['valor_total'] : 0;
        }

        public static function obtenerIdCliente($mesa){
            $objDataBase = DB::obtenerInstancia();
            $consulta = $objDataBase->prepararConsulta("SELECT * FROM pedido WHERE mesa=:mesa");
            $consulta->bindParam(':mesa', $mesa, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            return $resultado['cliente'];
        }


        public static function MesaUsada() {
            $objDataBase = DB::obtenerInstancia();
            
            // Consulta SQL para obtener la mesa más usada
            $consulta = $objDataBase->prepararConsulta("SELECT mesa, COUNT(*) as cantidad_pedidos
                                                        FROM pedido
                                                        GROUP BY mesa
                                                        ORDER BY cantidad_pedidos DESC
                                                        LIMIT 1");
            
            $consulta->execute();
            $mesaMasUsada = $consulta->fetch(PDO::FETCH_ASSOC);
            
            return $mesaMasUsada;
        }

        
}



