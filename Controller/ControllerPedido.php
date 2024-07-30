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
        $pedido->idEmpleado = $datos['idEmpleado'];

        
        if (isset($files['foto']) && $files['foto']->getError() === UPLOAD_ERR_OK) {
            $pedido->foto = $files['foto'];
        } else {
            $pedido->foto = null; 
        }
        $sector=Usuario::ObtenerSectorPorId($datos['idEmpleado']);
        var_dump($pedido->mesa);
        $estado=Mesa::ObtenerEstadoMesaPorId($pedido->mesa);
        try {
            if($estado=="abierta" && $sector==="mozo"){
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
        if(Pedido::PedidoExiste($mesa,$pedido)){
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
            echo "<br>";
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

        
        try{
            if(Pedido::productosListos($mesa)){

                if(Pedido::servidoConDemora($mesa)){
                    //Pedido::CambiarEstadoMesaYpedido($mesa,"servido con demora");
                }else{
                    //Pedido::CambiarEstadoMesaYpedido($mesa,"servido");
                }
                $respuesta = json_encode(["Pedido actualizado con exito"]);
                $response->getBody()->write($respuesta);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }else{
                $respuesta = json_encode(['error' => 'No todos los productos estan listos']);
                $response->getBody()->write($respuesta);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
            }
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

        try{
            Pedido::CambiarEstadoMesaYpedido($mesa,$estado);
            $precio=Pedido::CalcularValorTotalPorMesa($mesa);
            $idCliente=Pedido::obtenerIdCliente($mesa);
            Cliente::asignarMesaCliente($mesa,$idCliente);
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
            if(Pedido::consultarEstadoMesa($mesa,$idPedido,"con cliente pagando")){

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
            }else{
                $respuesta = json_encode(['error' => 'La mesa no se encuentra abonando ' ]);
                $response->getBody()->write($respuesta);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
            }
        }catch(Exception $e) {
            $respuesta = json_encode(['error' => 'Error al generar la encuesta: ' . $e->getMessage()]);
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

    public function TraerPeoresComentarios($request, $response, $args)
    {
        
        $encuesta = new Encuesta();
        $comentarios = $encuesta ->peoresComentarios();      
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

    public function MesaMenosUsada($request, $response, $args){

        $resultado=Pedido::MesaMenosUsada();
        $respuesta = json_encode(["La mesa más menos es la número " . $resultado['mesa'] . " con " . $resultado['cantidad_pedidos'] . " pedidos."]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
    public function mesaQueMasFacturo($request, $response, $args) {
        $resultado = Pedido::obtenerMesaQueMasFacturo();
        
        if ($resultado) {
            $respuesta = json_encode([
                "mensaje" => "La mesa que más facturó es la " . $resultado['mesa'] . " con un total de " . $resultado['valor_total'] . " pesos."
            ]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $respuesta = json_encode([
                "error" => "No se pudo obtener la información de la mesa que más facturó."
            ]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
    public function mesaQueMenosFacturo($request, $response, $args) {
        $resultado = Pedido::obtenerMesaQueMenosFacturo();
        
        if ($resultado) {
            $respuesta = json_encode([
                "mensaje" => "La mesa que menos facturó es la " . $resultado['mesa'] . " con un total de " . $resultado['valor_total'] . " pesos."
            ]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $respuesta = json_encode([
                "error" => "No se pudo obtener la información de la mesa que más facturó."
            ]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function facturacionEntreFechas($request, $response, $args){
        $datos = $request->getParsedBody();
        $fechaInicio = $datos['fechaInicio'];
        $fechaFin = $datos['fechaFin'];
        try {
            $resultado = Pedido::obtenerFacturacionEntreFechas($fechaInicio, $fechaFin);

            if ($resultado) {
                $respuesta = json_encode([
                    "mensaje" => "Facturación de las mesas entre " . $fechaInicio . " y " . $fechaFin,
                    "facturacion" => $resultado
                ]);
                $response->getBody()->write($respuesta);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } else {
                $respuesta = json_encode([
                    "error" => "No se encontraron datos de facturación entre las fechas proporcionadas."
                ]);
                $response->getBody()->write($respuesta);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            }
        } catch (Exception $e) {
            $respuesta = json_encode([
                "error" => "Error al obtener la facturación: " . $e->getMessage()
            ]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function verPedidosConDemora($request, $response, $args){
        $pedidosConDemora = Pedido::verPedidosConDemora();

        if (empty($pedidosConDemora)) {
            $response->getBody()->write(json_encode(["message" => "No se encontraron pedidos con demora."]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode($pedidosConDemora));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

}



