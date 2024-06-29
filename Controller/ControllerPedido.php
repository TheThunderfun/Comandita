<?php
include_once '../Models/Pedido.php';
include_once '../Models/Mesa.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

Class ControllerPedido{
     public function cargarUno(Request $request, Response $response, array $args) {
        $datos = $request->getParsedBody();
        $files =$request->getUploadedFiles();
        $pedido = new Pedido();
        $pedido->cliente = $datos['cliente'];
        $pedido->mesa = $datos['mesa'];
        $pedido->estado = "en preparacion";
        $pedido->idEmpleado = $datos['idEmpleado'];
       
        if (isset($files['foto']) && $files['foto']->getError() === UPLOAD_ERR_OK) {
            $pedido->foto = $files['foto'];
        } else {
            $pedido->foto = null; 
        }
        try {
            $pedido->CrearPedido();
            $respuesta = json_encode(['mensaje' => 'Pedido creado con exito']);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $respuesta = json_encode(['error' => 'Error al crear pedido: ' . $e->getMessage()]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }


}