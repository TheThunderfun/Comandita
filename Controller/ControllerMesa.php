<?php 
include_once '../Models/Mesa.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
Class ControllerMesa{
    public function cargarUno(Request $request, Response $response, $args) {
        $datos = $request->getParsedBody();
    
        $mesa = new Mesa();
        $mesa->estado ="cerrada";//strtolower($datos['estado']); 
        $mesa->fechaAlta = date('Y-m-d'); 
    
        try {
            $mesa->CrearMesa();
            $respuesta = json_encode(['mensaje' => 'Mesa creada con exito']);
            $response->getBody()->write($respuesta);
             Mesa::ListarTodos();
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $respuesta = json_encode(['error' => 'Error al crear mesa: ' . $e->getMessage()]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }

    }

}