<?php
include_once '../DataBase/DB.php';
include_once '../Archivos/Csv.php';
Class Producto{

    public $id;
    public $sector;
    public $nombre;
    public $precio;
    public $stock;
    public $tiempoPreparacion;
    public $fechaAlta;

    public function crearProducto()
        {
            $objDataBase = DB::obtenerInstancia();
            $consulta = $objDataBase->prepararConsulta("INSERT INTO producto 
            (sector, nombre, precio,stock, tiempoPreparacion,fechaAlta) VALUES (:sector, :nombre, :precio,:stock, :tiempoPreparacion,:fechaAlta)");
            
            $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
            $consulta->bindValue(':stock', $this->stock, PDO::PARAM_STR);
            $consulta->bindValue(':tiempoPreparacion', $this->tiempoPreparacion, PDO::PARAM_STR);
            $consulta->bindValue(':fechaAlta', $this->fechaAlta, PDO::PARAM_STR);
            
            $consulta->execute();    
            return $objDataBase->UltimoId();
        }


        public static function obtenerTodos() {
                $objDataBase = DB::obtenerInstancia();
                $consulta = $objDataBase->prepararConsulta("SELECT * FROM producto");
                $consulta->execute();
                return $consulta->fetchAll(PDO::FETCH_CLASS,'Producto');
        }

        public static function listarProductos() {
            $productos = Producto::ObtenerTodos();

            foreach ($productos as $producto) {
            echo "ID: " . $producto->id . "<br>";
            echo "Nombre: " . $producto->nombre . "<br>";
            echo "Sector: " . $producto->sector . "<br>";
            echo "Precio: " . $producto->precio . "<br>";
            echo "Stock: " . $producto->stock . "<br>";
            echo "Fecha de Alta: " . $producto->fechaAlta . "<br>";
            echo "Tiempo de preparacion en minutos: " . $producto->tiempoPreparacion . "<br>";
            
            }
        }

        public static function ActualizarStock($cantidad,$nombreProducto){
            $objDataBase = DB::obtenerInstancia();

            
            $consulta_id_producto = $objDataBase->prepararConsulta("SELECT id FROM producto WHERE nombre = :nombreProducto");
            $consulta_id_producto->bindParam(':nombreProducto', $nombreProducto, PDO::PARAM_STR);
            $consulta_id_producto->execute();
    
            
            if ($consulta_id_producto->rowCount() > 0) {
                $fila = $consulta_id_producto->fetch(PDO::FETCH_ASSOC);
                $id_producto = $fila['id'];
    
                
                $consulta_actualizacion = $objDataBase->prepararConsulta("UPDATE producto SET stock = stock - :cantidad WHERE id = :id_producto");
                $consulta_actualizacion->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
                $consulta_actualizacion->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
                $consulta_actualizacion->execute();
            }
        }

        public static function CargarCSV($archivo)
        {
            $array = Csv::LeerCsv($archivo);
          
            for($i = 0; $i < sizeof($array); $i++)
            {             
                $campos = explode(",", $array[$i]);               
                
                $producto = new Producto();
                $producto->id = $campos[0];
                $producto->sector = $campos[1];
                $producto->nombre = $campos[2];
                $producto->precio = $campos[3];
                $producto->stock = $campos[4];
                $producto->tiempoPreparacion = $campos[5];
                $producto->fechaAlta = $campos[6];

                
                $producto->crearProducto();
            }
        }

        public static function productoExiste($nombreProducto) {
            $objDataBase = DB::obtenerInstancia();
            $consulta = $objDataBase->prepararConsulta("SELECT COUNT(*) as cantidad FROM producto WHERE nombre = :nombreProducto");
            $consulta->bindParam(':nombreProducto', $nombreProducto, PDO::PARAM_STR);
            $consulta->execute();
        
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        
            return $resultado['cantidad'] > 0;
        }

}