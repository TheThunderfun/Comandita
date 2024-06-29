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
    
        $consulta = $objDataBase->prepararConsulta("INSERT INTO pedido (cliente_id, mesa_id, estado, tiempo_Preparacion, idEmpleado) 
            VALUES (:cliente, :mesa, :estado, :tiempoPreparacion, :idEmpleado)");
    
        
        $consulta->bindValue(':cliente', $this->cliente, PDO::PARAM_STR);
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_INT); 
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoPreparacion', self::VerTiempoEstimadoPedido($this->id), PDO::PARAM_INT); 
        $consulta->bindValue(':idEmpleado', $this->idEmpleado, PDO::PARAM_INT);
    
     
        if ($this->foto != null) {
            $this->GuardarImagen($this->mesa);
        }
        
        $consulta->execute();

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
           $stmt = $db->prepararConsulta("UPDATE pedido SET foto = ? WHERE mesa_id = ?");
           $stmt->execute([$rutaCompletaImagen, $mesa]);
        
        }

        public static function VerTiempoEstimadoPedido($idPedido){
            $objDataBase = DB::obtenerInstancia();

            $consultaEsperando = $objDataBase->prepararConsulta("SELECT COUNT(*) as cantidad FROM mesa WHERE estado = 'en preparacion'");
            $consultaEsperando->execute();
            $resultadoEsperando = $consultaEsperando->fetch(PDO::FETCH_ASSOC);
            $cantEsperandoPedido = $resultadoEsperando['cantidad'];
            

            $consultaTotal = $objDataBase->prepararConsulta("SELECT COUNT(*) as cantidadTotal FROM mesa");
            $consultaTotal->execute();
            $resultadoTotal = $consultaTotal->fetch(PDO::FETCH_ASSOC);
            $cantTotalMesas = $resultadoTotal['cantidadTotal'];

            $aux = ProductosPedido::SumarTiempoProductos($idPedido);
            if ($cantEsperandoPedido == $cantTotalMesas) {
                $tiempoEstimado = $aux * 1.7; 
            } else {
                $tiempoEstimado = $aux+10; 
            }
            return $tiempoEstimado;
        }

        public static function ActulizarTiempoEstimado($tiempoEstimado,$idPedido){
            $objDataBase = DB::obtenerInstancia();
            $consultaEsperando = $objDataBase->prepararConsulta("UPDATE pedido SET tiempo_preparacion = $tiempoEstimado WHERE id = $idPedido");
            $consultaEsperando->execute();
        }
}



