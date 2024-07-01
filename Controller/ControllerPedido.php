<?php
include_once '../Models/Pedido.php';
include_once '../Models/Mesa.php';
include_once '../Models/Cliente.php';
include_once '../Models/Encuesta.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

Class ControllerPedido{
     public function cargarUno($request,$response, array $args) {
        $datos = $request->getParsedBody();
        $files =$request->getUploadedFiles();
        $pedido = new Pedido();
        $pedido->cliente = $datos['cliente'];
        $pedido->mesa = $datos['mesa'];
        //$pedido->estado = "en preparacion";
        $pedido->idEmpleado = $datos['idEmpleado'];

        
        if (isset($files['foto']) && $files['foto']->getError() === UPLOAD_ERR_OK) {
            $pedido->foto = $files['foto'];
        } else {
            $pedido->foto = null; 
        }
        $estado=Mesa::ObtenerEstadoMesaPorId($pedido->mesa);
        var_dump($estado);
        try {
            if($estado=="abierta"){
            $pedido->CrearPedido();
            $respuesta = json_encode(['mensaje' => 'Pedido creado con exito']);
            $response->getBody()->write($respuesta);
            Pedido::CambiarEstadoMesaYpedido($pedido->mesa,"con cliente esperando pedido");
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }else{
                $respuesta = json_encode(['mensaje' => 'La mesa no esta abierta para realzar el pedido']);
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

    public function Servir($request,$response){
        $data=$request->getParsedBody();

        $mesa=$data['mesa'];
        $estado=$data['estado'];

        //var_dump($mesa);
        try{
            Pedido::CambiarEstadoMesaYpedido($mesa,$estado);
            $respuesta = json_encode(["Pedido actualizado con exito"]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } catch (Exception $e) {
            $respuesta = json_encode(['error' => 'Error al crear pedido: ' . $e->getMessage()]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function Cobrar($request,$response){
        $data=$request->getParsedBody();

        $mesa=$data['mesa'];
        $estado=$data['estado'];

        //var_dump($mesa);
        try{
            Pedido::CambiarEstadoMesaYpedido($mesa,$estado);
            $precio=Pedido::CalcularValorTotalPorMesa($mesa);
            $idCliente=Pedido::obtenerIdCliente($mesa);
            var_dump($precio,$idCliente);
            Cliente::actualizarCuenta($precio,$idCliente);
            $respuesta = json_encode(["En proceso de cobranza"]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } catch (Exception $e) {
            $respuesta = json_encode(['error' => 'Error al cobrar: ' . $e->getMessage()]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public static function Pagar($request,$response){
        $data=$request->getParsedBody();

        $puntuacionMozo = $data['puntuacionMozo'];
        $puntuacionCocina = $data['puntuacionCocina'];    
        $puntuacionMesa = $data['puntuacionMesa'];
        $puntuacionBebidas = $data['puntuacionBebidas']; 
        $comentario = $data['comentario'];
        $mesa = $data['mesa']; 
        $idPedido = $data['pedido'];
        
        $encuesta= new Encuesta();
        $encuesta->puntuacionMozo = $puntuacionMozo;  
        $encuesta->puntuacionCocina = $puntuacionCocina;  
        $encuesta->puntuacionMesa = $puntuacionMesa;  
        $encuesta->puntuacionBebidas = $puntuacionBebidas;  
        $encuesta->comentario = $comentario;  
        $encuesta->codigoMesa = $mesa;  
        $encuesta->codigoPedido = $idPedido;  
        $encuesta->fechaAlta = date ('Y-m-d'); 
        
        try{
        $encuesta->crearEncuesta();
        Pedido::CambiarEstadoMesa($mesa,"abierta");
        $respuesta = json_encode(array("mensaje" => "La encuesta fue contestada con exito y el pago fue recibido! ",
        "puntuacionMozo"=> $encuesta->puntuacionMozo ,  
        "puntuacionCocina"=>$encuesta->puntuacionCocina ,  
        "puntuacionMesa"=>$encuesta->puntuacionMesa ,  
        "puntuacionBebidas"=>$encuesta->puntuacionBebidas,  
        "comentario"=>$encuesta->comentario ,  
        "codigoMesa"=>$encuesta->codigoMesa ,  
        "codigoPedido"=>$encuesta->codigoPedido ,),JSON_PRETTY_PRINT);
        $response->getBody()->write($respuesta);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }catch(Exception $e) {
            $respuesta = json_encode(['error' => 'Error al cobrar: ' . $e->getMessage()]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }

    }


    public function TraerMejoresComentarios($request, $response, $args)
    {
        
        $encuesta = new Encuesta();
        $comentarios = $encuesta ->mejoresComentarios();      
        $payload = json_encode($comentarios,JSON_PRETTY_PRINT);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MesaMasUsada($request, $response, $args){

        $resultado=Pedido::MesaUsada();
        $respuesta = json_encode(["La mesa más usada es la número " . $resultado['mesa'] . " con " . $resultado['cantidad_pedidos'] . " pedidos."]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
}