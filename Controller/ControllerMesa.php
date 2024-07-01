<?php 
include_once '../Models/Mesa.php';
include_once '../Models/Pedido.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
Class ControllerMesa{
    public function cargarUno(Request $request, Response $response, $args) {
        $datos = $request->getParsedBody();
    
        $mesa = new Mesa();
        $mesa->estado ="abierta";//strtolower($datos['estado']); 
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

    public function ListarMesas($request, $response, $args) {
        try {
            $mesas = Mesa::ObtenerListadoMesas();
            $respuesta = json_encode($mesas);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $respuesta = json_encode(['error' => 'Error al obtener el listado de mesas: ' . $e->getMessage()]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function ModificarMesa($request, $response, $args){
        $datos = $request->getParsedBody();
       // $mesa=$args['mesa'];
        $mesa=$datos['mesa'];
        $estado=$datos['estado'];
        //var_dump($mesa);
        try{
            Pedido::CambiarEstadoMesaYpedido($mesa,$estado);
            $respuesta = json_encode(["Mesa actualizada con exito"]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } catch (Exception $e) {
            $respuesta = json_encode(['error' => 'Error al crear pedido: ' . $e->getMessage()]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

}