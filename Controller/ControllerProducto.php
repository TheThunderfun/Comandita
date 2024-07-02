<?php 
include_once '../Models/Producto.php'; 
include_once '../Archivos/Csv.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
Class ControllerProducto{
    public function cargarUno(Request $request, Response $response, $args) {
        $datos = $request->getParsedBody();
    
        $producto = new Producto();
        $producto->sector = $datos['sector'];
        $producto->nombre = $datos['nombre'];
        $producto->precio = $datos['precio'];
        $producto->stock=$datos['stock'];
        $producto->tiempoPreparacion = $datos['tiempoPreparacion'];
        $producto->fechaAlta = date('Y-m-d'); 
    
        try {
            $producto->crearProducto();
            $respuesta = json_encode(['mensaje' => 'Producto creado con exito']);
            $response->getBody()->write($respuesta);
            //Producto::listarProductos();
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $respuesta = json_encode(['error' => 'Error al crear producto: ' . $e->getMessage()]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function exportarTabla($request, $response, $args)
    {
        try {
            
            $csvContent = Csv::exportarTabla('producto', 'Producto');
    
            //var_dump($csvContent);
            // $response = $response->withHeader('Content-Description', 'File Transfer')
            //                      ->withHeader('Content-Type', 'text/csv')
            //                      ->withHeader('Content-Disposition', 'attachment; filename="producto.csv"')
            //                      ->withHeader('Expires', '0')
            //                      ->withHeader('Cache-Control', 'must-revalidate')
            //                      ->withHeader('Pragma', 'public')
            //                      ->withHeader('Content-Length', strlen($csvContent));
            $response->getBody()->write($csvContent);
            return $response;
    
        } catch (\Throwable $mensaje) {
     
            $response->getBody()->write("Error al exportar: " . $mensaje->getMessage());
            return $response->withStatus(500); 
        }
    }



    public function ImportarTabla($request, $response, $args)
    {
        try
        {   $data = $request->getParsedBody();
            $archivo = ($_FILES["archivo"]);          
            Producto::CargarCSV($archivo["tmp_name"]);
            $payload = json_encode("Carga exitosa.");
            $response->getBody()->write($payload);
            $newResponse = $response->withHeader('Content-Type', 'application/json');
        }
        catch(Throwable $mensaje)
        {
            printf("Error al listar: <br> $mensaje .<br>");
        }
        finally
        {
            return $newResponse;
        }    
    }
}