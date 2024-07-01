<?php 
include_once '../DataBase/DB.php';
class Mesa{
    public $id;
    public $codigoMesa;    
    public $estado;//cerrada, cliente esperando pedido, Cliente comiendo, cliente pagando
    public $fechaAlta;


    public function CrearMesa()
    {
        $objDataBase = DB::obtenerInstancia();
        $codigoAlfanumerico = Mesa::GenerarCodigo(5);


        $this->codigoMesa = $codigoAlfanumerico;

        $consulta = $objDataBase->prepararConsulta("INSERT INTO mesa (codigoMesa, estado, fechaAlta) 
        VALUES (:codigoMesa, :estado,:fechaAlta)");           
        $consulta->bindValue(':codigoMesa', $this->codigoMesa);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', $this->fechaAlta, PDO::PARAM_STR);

        $consulta->execute();

        return $objDataBase->UltimoId();
        
    }

    public static function GenerarCodigo($length){

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
    

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
    
        return $randomString;
    }
    public static function obtenerTodos() {
        $objDataBase = DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("SELECT * FROM mesa");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS,'Mesa');
    }

    public static function ListarTodos(){
        $mesas=Mesa::obtenerTodos();

        foreach($mesas as $mesa){
            echo "ID: " . $mesa->id . "<br>";
            echo "Codigo de mesa: " . $mesa->codigoMesa . "<br>";
            echo "Fecha de alta: " . $mesa->fechaAlta . "<br>";
            echo "Estado: " . $mesa->estado . "<br>";
        }
    }

    // public static function CambiarEstadoMesa($codigoMesa,$estado){
    //     $objDataBase = DB::obtenerInstancia();

    //     $consulta_id_producto = $objDataBase->prepararConsulta("UPDATE mesa SET estado = :estado  WHERE codigoMesa = :codigoMesa");
    //     $consulta_id_producto->bindParam(':codigoMesa', $codigoMesa, PDO::PARAM_STR);
    //     $consulta_id_producto->bindParam(':estado', $estado, PDO::PARAM_STR);
    //     $consulta_id_producto->execute();
    // }

    public static function ObtenerEstadoMesaPorId($idMesa){
        $objDataBase = DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("SELECT estado FROM mesa WHERE codigoMesa = :idMesa");
        $consulta->bindParam(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->execute();
        $resultado=$consulta->fetch(PDO::FETCH_ASSOC);
        //var_dump($resultado);
        return $resultado['estado'];
    }

    public static function ObtenerListadoMesas() {
        $objDataBase = DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("SELECT id, codigoMesa, estado, fechaAlta FROM mesa");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}



