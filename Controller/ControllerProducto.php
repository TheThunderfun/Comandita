<?php 
include_once '../Models/Producto.php'; 
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
}