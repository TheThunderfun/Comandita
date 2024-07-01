<?php
use Slim\Psr7\Environment;

Class DB{
    private static $objDB=null;
    private $DBPDO;

    private function __construct()
    {
       try {
            $this->DBPDO = new PDO('mysql:host='.$_ENV['MYSQL_HOST'].';dbname='.$_ENV['MYSQL_DB'].';charset=utf8', $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASS'], array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));                       
            $this->DBPDO->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            print "Error: " . $e->getMessage();
            die();
        }
    }

    public static function obtenerInstancia()
    {
        if (!isset(self::$objDB)) {
            self::$objDB = new DB();
        }
        return self::$objDB;
    }

    public  function PrepararConsulta($sql){
        return $this->DBPDO->prepare($sql);
    }


    public function UltimoId(){
        {
            return $this->DBPDO->lastInsertId();
        }
    }

    public static function ObtenerConsulta($sql, $clase=null)
    {
        try
        {   //var_dump($sql);
            $conexion = DB::obtenerInstancia();
            $consulta = $conexion->prepararConsulta($sql);
            //var_dump($consulta);
            $consulta->execute();
            $retorno = $consulta->fetchAll(PDO::FETCH_CLASS, $clase);
        }
        catch(Throwable $mensaje)
        {
            printf("Error de la BD: <br> $mensaje .<br>");
        }
        finally
        {
            return $retorno;
        }    
    }

    public static function obtenerTodos($tabla, $clase)
    {
        $sql = "SELECT * FROM $tabla;";
        return DB::ObtenerConsulta($sql, $clase);
    }
}