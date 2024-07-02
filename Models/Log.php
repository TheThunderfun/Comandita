<?php

class Log{
    public $id;
    public $dni;
    public $sector;
    public $fecha;
    public $metodo;
    public $url;

    public function CargarUno(){
        $objDataBase = DB::obtenerInstancia();
        $consulta = $objDataBase->prepararConsulta("INSERT INTO logs (dni,sector, metodo, url) VALUES (:dni,:sector,:metodo, :url)");


        $consulta->bindValue(':dni', $this->dni, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
       // $consulta->bindValue(':fecha', date('Y-m-d'), PDO::PARAM_STR);
        $consulta -> bindParam(':metodo', $this->metodo);
        $consulta -> bindParam(':url', $this->url);

        $consulta->execute();

    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = DB::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM logs;");
        
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Log');
    }
    public static function CantidadDeOperacionesPorSector()
    {
        $objAccesoDatos = DB::obtenerInstancia();
    
      
        $consulta = $objAccesoDatos->prepararConsulta("SELECT usuario.tipo AS nombre_sector, COUNT(logs.id) AS cantidad_operaciones
        FROM logs
        INNER JOIN usuario ON logs.sector = usuario.tipo
        GROUP BY usuario.tipo
        ");
    
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function CantidadDeOperacionesPorEmpleadoYSector()
    {   
        //var_dump("hola");
        $objAccesoDatos = DB::obtenerInstancia();
    
        $consulta = $objAccesoDatos->prepararConsulta("SELECT usuario.tipo AS nombre_sector, usuario.nombre AS nombre_empleado, logs.dni, COUNT(logs.id) AS cantidad_operaciones
            FROM logs
            INNER JOIN usuario ON logs.sector = usuario.tipo
            GROUP BY logs.dni, usuario.tipo
            ORDER BY usuario.tipo ASC
        ");
    
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } public static function DiasYHorariosPorEmpleado($dni)
    {
        $objAccesoDatos = DB::obtenerInstancia(); 
        var_dump($dni);
        $consulta = $objAccesoDatos->prepararConsulta("SELECT logs.fecha
            FROM logs
            WHERE logs.dni = :dni
            ORDER BY logs.fecha
        ");
        
        $consulta->bindValue(':dni', $dni, PDO::PARAM_INT);
        $consulta->execute();
    
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
        $consultaEmpleado = $objAccesoDatos->prepararConsulta("SELECT usuario.nombre, usuario.tipo AS nombre_sector
        FROM usuario
        WHERE usuario.dni = :dni
    ");
    
        $consultaEmpleado->bindValue(':dni', $dni, PDO::PARAM_INT);
        $consultaEmpleado->execute();
    
        $empleado = $consultaEmpleado->fetch(PDO::FETCH_ASSOC);
    
        $empleado['dias_y_horarios'] = $resultados;
    
        return $empleado;
    }
    
}