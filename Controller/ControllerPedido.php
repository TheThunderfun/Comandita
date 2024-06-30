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

        Mesa::CambiarEstadoMesa($pedido->mesa,"con cliente esperando pedido");
       
        if (isset($files['foto']) && $files['foto']->getError() === UPLOAD_ERR_OK) {
            $pedido->foto = $files['foto'];
        } else {
            $pedido->foto = null; 
        }
        $estado=Mesa::ObtenerEstadoMesaPorId($pedido->mesa);
        try {
            if($estado!="cerrada"){
            $pedido->CrearPedido();
            $respuesta = json_encode(['mensaje' => 'Pedido creado con exito']);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }
        } catch (Exception $e) {
            $respuesta = json_encode(['error' => 'Error al crear pedido: ' . $e->getMessage()]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
    public function ConsultarDemora($request, $response, $args){
        $data = $request->getParsedBody();

        $mesa=$data['mesa'];
        $pedido=$data['pedido'];

        if(Pedido::PedidoExiste($mesa,$pedido)!=false){
            $tiempo=ProductosPedido::VerTiempoEstimadoPedido($pedido);
            Pedido::ActulizarTiempoEstimado($tiempo,$pedido);
           // echo "El tiempo de demora del pedido es:". $tiempo;
            $respuesta = json_encode(['Pedido' => "El tiempo de demora del pedido es:". $tiempo]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }else{
            $respuesta = json_encode(['Error' => 'Datos incorrectos']);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public  function ListarDemoraPedidos($request,$response){
        $productos=Pedido::ObtenerTodos();

        foreach($productos as $producto){
            echo "El id del pedido es:",$producto->id."<br>";
            echo "El cliente del pedido es:",$producto->cliente."<br>";
            echo "La mesa del pedido es:",$producto->mesa."<br>";
            echo "La demora del pedido es:",$producto->tiempoPreparacionEstimado."<br>";
            echo "El empleado a cargo del pedido es:",$producto->idEmpleado."<br>";
        }

        $respuesta = json_encode(['Finalizo el listado:' => 'Ya no hay mas pedidos']);
        $response->getBody()->write($respuesta);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }

}