<?php
include_once '../DataBase/DB.php';
Class Cliente {

    public $id;
    public $nombre;
    public $apellido;
    public $dni;
    public $fechaCobro;

    public $mesa;

    public function __construct() { }

    public function CargarUno(){
        
        $objDataBase=DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("INSERT INTO cliente (nombre, apellido,dni) VALUES (:nombre, :apellido,:dni)");


        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':dni', $this->dni, PDO::PARAM_STR);

        $consulta->execute();
        return $objDataBase->UltimoId();
    }

    public static function obtenerTodosCliente()
    {
            $objDataBase = DB::obtenerInstancia();
            $consulta = $objDataBase->prepararConsulta("SELECT * FROM cliente");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function actualizarCuenta($precio,$idCliente){
        $objDataBase=DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta(
            "UPDATE cliente 
             SET cuenta = :precio, fechaCobro = :fecha 
             WHERE id = :idCliente"
        );
        
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha=date('Y-m-d H:i:s');

        $consulta->bindValue(':precio', $precio, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->bindValue(':idCliente', $idCliente, PDO::PARAM_STR);
        $consulta->execute();
    }


    public static function asignarMesaCliente($mesa,$idCliente){
        $objDataBase=DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("UPDATE cliente SET mesa=:mesa WHERE id = :idCliente");
        $consulta->bindValue(':mesa', $mesa, PDO::PARAM_STR);
        $consulta->bindValue(':idCliente', $idCliente, PDO::PARAM_STR);
        $consulta->execute();
    }

}

