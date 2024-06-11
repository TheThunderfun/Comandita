<?php 
include_once "DB.php";
class Mesa{
    public $id;
    public $codigoMesa;    
    public $estado;


    public function crearMesa()
    {
        $objAccesoDatos = DB::obtenerInstancia();
         // Genera un código alfanumérico de 5 caracteres
        $codigoAlfanumerico = Mesa::generarCodigo(5);
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesa (codigoMesa, estado) 
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



