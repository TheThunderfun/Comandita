<?php

include_once '../DataBase/DB.php';
Class Usuario {

    public $id;
    public $nombre;
    public $apellido;
    public $dni;
    public $tipo;//admin mozo bartender cocinero cerveceros
    public $fechaAlta;
    public $clave;
    public $estado;
    
    public function CrearUsuario()
    {
        $objDataBase = DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("INSERT INTO usuario (nombre,apellido,dni, tipo,fechaAlta,clave,estado)
         VALUES (:nombre, :apellido,:dni, :tipo,  :fechaAlta, :clave,:estado)");
       
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':dni', $this->dni, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_INT);
        $consulta->bindValue(':fechaAlta', $this->fechaAlta, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave,PDO::PARAM_STR);
        $consulta->bindValue(':estado', "activo",PDO::PARAM_STR);


        $consulta->execute();
        return $objDataBase->UltimoId();
    }
    public function ModificarUno()
    {
        $objDataBase = DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("UPDATE usuario SET nombre = :nombre, apellido = :apellido, tipo = :tipo, clave = :clave WHERE dni = :dni");
    
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_INT);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':dni', $this->dni, PDO::PARAM_STR);
    
        $consulta->execute();
        return $consulta->rowCount(); 
    }

    public static function BajaUno($dniUsuario){
        $objDataBase = DB::obtenerInstancia();
        $estado="inactivo";
        $consulta=$objDataBase->PrepararConsulta("UPDATE usuario set estado = :estado WHERE dni = :dni");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':dni', $dniUsuario, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Usuario');

    }
    public static function obtenerPassword($dni)
    {
        $objAccesoDato = DB::obtenerInstancia();
        
        $consulta = $objAccesoDato->prepararConsulta("SELECT clave FROM usuario 
        WHERE dni = :dni");
        $consulta->bindValue(':dni', $dni, PDO::PARAM_INT);
        $consulta->execute();       
        $resultado=$consulta->fetch(PDO::FETCH_ASSOC);
        
        return $resultado['clave'];
    }

    public static function ObtenerUsuarioPorDni($dni){
        $objAccesoDato = DB::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM usuario WHERE dni = :dni");
        $consulta->bindValue(':dni', $dni, PDO::PARAM_INT);
        $consulta->execute();  
        return $consulta->fetch(PDO::FETCH_ASSOC);   
    }

    public static function ObtenerTodosUsuarios() {
        $objDataBase = DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("SELECT * FROM usuario");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }


    public static function ListarUsuarios(){
        $usuarios = Usuario::ObtenerTodosUsuarios();

        foreach ($usuarios as $usuario) {
        echo "ID: " . $usuario->id . "<br>";
        echo "Nombre: " . $usuario->nombre . "<br>";
        echo "Apellido: " . $usuario->apellido . "<br>";
        echo "Tipo: " . $usuario->tipo . "<br>";
        echo "Fecha de Alta: " . $usuario->fechaAlta . "<br>";
        echo "Clave: " . $usuario->clave . "<br><br>";
        }
    }
}