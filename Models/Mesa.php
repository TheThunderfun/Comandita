<?php 
include_once "DB.php";
class Mesa{
    public $id;
    public $codigoMesa;    
    public $estado=1;//0=cerrada, 1=cliente esperando pedido, 2=Cliente comiendo, 3=cliente pagando


    public function crearMesa()
    {
        $objDataBase = DB::obtenerInstancia();

        $codigoAlfanumerico = Mesa::generarCodigo(5);
        $consulta = $objDataBase->prepararConsulta("INSERT INTO mesa (codigoMesa, estado) 
        VALUES (:codigoMesa, :estado)");           
        $consulta->bindValue(':codigoMesa', $this->codigoMesa = $codigoAlfanumerico);

        $consulta->execute();
        
    }

    public function GenerarCodigo($length){

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
    

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
    
        return $randomString;
    }
    
}



