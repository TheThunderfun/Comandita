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

        
}



