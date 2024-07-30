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
            try {
                $objDataBase = DB::obtenerInstancia();
                $consulta = $objDataBase->prepararConsulta("UPDATE pedido SET tiempoEstimado = :tiempo WHERE id = :idPedido");
                $consulta->bindParam(':tiempo', $tiempoEstimado, PDO::PARAM_INT);
                $consulta->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
        
                // Depuración: Registro de la consulta SQL
                error_log("UPDATE pedido SET tiempoEstimado = $tiempoEstimado WHERE id = $idPedido");
        
                $consulta->execute();
            } catch (PDOException $e) {
                error_log("Error al actualizar el tiempo estimado: " . $e->getMessage());
            }
        }

        public static function PedidoExiste($mesa,$pedido){
            try {
                $objDataBase = DB::obtenerInstancia();
                $consulta = $objDataBase->prepararConsulta("SELECT COUNT(*) as cantidad FROM pedido WHERE mesa = :codigoMesa AND id = :idPedido");
                $consulta->bindParam(':codigoMesa', $mesa, PDO::PARAM_STR);
                $consulta->bindParam(':idPedido', $pedido, PDO::PARAM_INT);        
                $consulta->execute();
                $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                return $resultado['cantidad'] > 0;
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

        public static function CambiarEstadoMesaYpedido($codigoMesa, $estado) {
            $objDataBase = DB::obtenerInstancia();
    
            try {

                $consultaMesa = $objDataBase->prepararConsulta("UPDATE mesa SET estado = :estado WHERE codigoMesa = :codigoMesa");
                $consultaMesa->bindParam(':codigoMesa', $codigoMesa, PDO::PARAM_STR);
                $consultaMesa->bindParam(':estado', $estado, PDO::PARAM_STR);
                $consultaMesa->execute();
        
                $consultaPedido = $objDataBase->prepararConsulta("UPDATE pedido SET estado = :estado WHERE mesa = :codigoMesa");
                $consultaPedido->bindParam(':codigoMesa', $codigoMesa, PDO::PARAM_STR);
                $consultaPedido->bindParam(':estado', $estado, PDO::PARAM_STR);
                $consultaPedido->execute();

                $consultaProductos = $objDataBase->prepararConsulta("SELECT id FROM pedido WHERE mesa=:mesa ");
                $consultaProductos->bindValue(':mesa', $codigoMesa, PDO::PARAM_STR);
                $consultaProductos->execute();
                $pedido = $consultaProductos->fetch(PDO::FETCH_ASSOC);
                $pedidoId=$pedido["id"];
                $consultaProductos = $objDataBase->prepararConsulta("UPDATE productos_pedidos SET estado = :estado WHERE pedido_id = :pedidoId");
                $consultaProductos->bindParam(':pedidoId', $pedidoId, PDO::PARAM_INT);
                $consultaProductos->bindParam(':estado', $estado, PDO::PARAM_STR);
                $consultaProductos->execute();

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
        public static function MesaMenosUsada() {
            $objDataBase = DB::obtenerInstancia();
            
            // Consulta SQL para obtener la mesa más usada
            $consulta = $objDataBase->prepararConsulta("SELECT mesa, COUNT(*) as cantidad_pedidos
                                                        FROM pedido
                                                        GROUP BY mesa
                                                        ORDER BY cantidad_pedidos ASC
                                                        LIMIT 1");
            
            $consulta->execute();
            $mesaMasUsada = $consulta->fetch(PDO::FETCH_ASSOC);
            
            return $mesaMasUsada;
        }

        public static function obtenerMesaQueMasFacturo(){
            $objDataBase = DB::obtenerInstancia();

            $consulta = $objDataBase->prepararConsulta(
                "SELECT pe.mesa, SUM(pp.cantidad * p.precio) AS valor_total 
                 FROM pedido pe 
                 JOIN productos_pedidos pp ON pe.id = pp.pedido_id 
                 JOIN producto p ON pp.producto = p.nombre 
                 GROUP BY pe.mesa 
                 ORDER BY valor_total DESC 
                 LIMIT 1"
            );
            $consulta->execute();
            $mesa= $consulta->fetch(PDO::FETCH_ASSOC);
            return $mesa;
        }

        
        public static function obtenerMesaQueMenosFacturo(){
            $objDataBase = DB::obtenerInstancia();

            $consulta = $objDataBase->prepararConsulta(
                "SELECT pe.mesa, SUM(pp.cantidad * p.precio) AS valor_total 
                FROM pedido pe 
                JOIN productos_pedidos pp ON pe.id = pp.pedido_id 
                JOIN producto p ON pp.producto = p.nombre 
                GROUP BY pe.mesa 
                ORDER BY valor_total ASC 
                LIMIT 1"
            );
            $consulta->execute();
            $mesa= $consulta->fetch(PDO::FETCH_ASSOC);
            return $mesa;
        }

        public static function obtenerFacturacionEntreFechas($fechaInicio, $fechaFin){
            $objDataBase = DB::obtenerInstancia();

            $consulta = $objDataBase->prepararConsulta(
                "SELECT mesa, SUM(total) AS valor_total
                FROM cliente
                WHERE fecha_facturacion BETWEEN :fecha_inicio AND :fecha_fin
                GROUP BY mesa
                ORDER BY valor_total DESC"
            );
            $consulta->bindValue(':fecha_inicio', $fechaInicio, PDO::PARAM_STR);
            $consulta->bindValue(':fecha_fin', $fechaFin, PDO::PARAM_STR);
            $consulta->execute();
            $facturacion = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $facturacion;
        }

        public static function productosListos($mesa){
            $objDataBase = DB::obtenerInstancia();
           
            $consulta = $objDataBase->prepararConsulta("SELECT id FROM pedido WHERE mesa=:mesa ");
            $consulta->bindValue(':mesa', $mesa, PDO::PARAM_STR);
            $consulta->execute();
            $pedido = $consulta->fetch(PDO::FETCH_ASSOC);
            if ($pedido) {
                $pedidoId = $pedido['id'];
            
                $consulta = $objDataBase->prepararConsulta("SELECT COUNT(*) AS totalProductos,SUM(CASE WHEN estado = 'listo para servir' THEN 1 ELSE 0 END) 
                AS productosListos FROM productos_pedidos WHERE pedido_id = :pedidoId");
                $consulta->bindValue(':pedidoId', $pedidoId, PDO::PARAM_INT);
                $consulta->execute();
                $resultado=$consulta->fetch(PDO::FETCH_ASSOC);
                
                if ($resultado['totalProductos'] == $resultado['productosListos']) {
                    return true; 
                } else {
                    return false; 
                }
            } else {
                return false; 
            }
            }
            


            public static function servidoConDemora($mesa){
                $objDataBase = DB::obtenerInstancia();
           
                $consulta = $objDataBase->prepararConsulta("SELECT id FROM pedido WHERE mesa=:mesa ");
                $consulta->bindValue(':mesa', $mesa, PDO::PARAM_STR);
                $consulta->execute();
                $pedido = $consulta->fetch(PDO::FETCH_ASSOC);
                $pedidoId = $pedido['id'];
                $consulta = $objDataBase->prepararConsulta("SELECT  MAX(tiempoEstimado) AS tiempoMaximo, 
                    MAX(fechaModf) AS fechaModificacionReciente 
                FROM 
                    productos_pedidos 
                WHERE 
                    pedido_id = :pedidoId
            ");

            $consulta->bindParam(':pedidoId', $pedidoId, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                
                $tiempoMaximoEstimado = $resultado['tiempoMaximo'];
                $fechaModificacionReciente = $resultado['fechaModificacionReciente'];


                $timezone = new DateTimeZone('America/Argentina/Buenos_Aires');

                $horaActual = new DateTime('now', $timezone);

                $fechaModificacion = new DateTime($fechaModificacionReciente, $timezone);


                $fechaModificacion->add(new DateInterval("PT{$tiempoMaximoEstimado}M"));

                if ($horaActual > $fechaModificacion) {
                    echo "El pedido se servirá con demora.";
                    return true;
                } else {
                    echo "El pedido no se servirá con demora.";
                    return false;
                }
            }
        }

        public static function verPedidosConDemora(){
            $objDataBase = DB::obtenerInstancia();
            $consulta = $objDataBase->prepararConsulta("SELECT * FROM pedido WHERE estado='servido con demora'");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }
    
        public static function consultarEstadoMesa($mesa,$pedido,$estado){
            try {
                $objDataBase = DB::obtenerInstancia();
                
      
                $consulta = $objDataBase->prepararConsulta(
                    "SELECT estado FROM pedido WHERE mesa = :mesa AND id = :pedido"
                );
                $consulta->bindValue(':mesa', $mesa, PDO::PARAM_STR);
                $consulta->bindValue(':pedido', $pedido, PDO::PARAM_INT);
                $consulta->execute();
        

                $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                //var_dump($resultado);

                if ($resultado && $resultado['estado'] == $estado) {
                    return true; 
                } else {
                    return false; 
                }
                
            } catch (PDOException $e) {

                error_log("Error al consultar estado de mesa: " . $e->getMessage());
                return false; 
            }
        }
    }



