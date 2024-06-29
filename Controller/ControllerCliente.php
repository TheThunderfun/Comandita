<?php
include_once '../Models/Cliente.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
Class ControllerCliente{
    public function CargarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();

        
      $cliente = new Cliente();
      $cliente->nombre = $parametros['nombre'];    
      $cliente->apellido=$parametros['apellido'];
      $cliente->dni=$parametros['dni'];
      

      try {
        $cliente->CargarUno();
        $respuesta = json_encode(['mensaje' => 'Cliente creado con exito']);
        $response->getBody()->write($respuesta);
        //Cliente::ListarTodos();
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e) {
        $respuesta = json_encode(['error' => 'Error al crear cliente: ' . $e->getMessage()]);
        $response->getBody()->write($respuesta);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
    }
}