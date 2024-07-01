<?php 
include_once '../Models/ProductosPedidos.php';
include_once '../Models/Producto.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Factory\AppFactory;
class ControllerProductosPedido{

    public function CargarUno($request, $response, $args) {

        $datos = $request->getParsedBody();

        $productos=new ProductosPedido();
        $productos->pedido_id=$datos['pedido_id'];
        $productos->producto=$datos['producto'];
        $productos->cantidad=$datos['cantidad'];
        if (isset($datos['tiempoEstimado'])) {
            $productos->tiempoEstimado = $datos['tiempoEstimado'];
        } else {
            // Si tiempoEstimado no está presente o es null, asignar null explícitamente
            $productos->tiempoEstimado = null;
        }
        //var_dump($productos);
        try {
            if(ProductosPedido::BuscarProductoPorNombre($productos->producto)!=null){
                $productos->CargarUno();
                $tiempoEstimado=ProductosPedido::VerTiempoEstimadoPedido($productos->pedido_id);
                //Pedido::ActulizarTiempoEstimado($tiempoEstimado,$productos->pedido_id);
                Producto::ActualizarStock($productos->cantidad,$productos->producto);
                $respuesta = json_encode(['mensaje' => 'Producto pedido con exito']);
                $response->getBody()->write($respuesta);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }
        } catch (Exception $e) {
            $respuesta = json_encode(['error' => 'El producto pedido no existe ' . $e->getMessage()]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
    public function ModificarUno($request, $response, $args){

        $data = $request->getParsedBody();

        $id=$data['id'];
        $tiempoEstimado=$data['tiempoEstimado'];
        $estado=$data['estado'];
        $sector = $args['sector'];
        //var_dump($args["sector"]);
        //var_dump(ProductosPedido::modificarTiempoPreparacion($id, $tiempoEstimado));
       
        try {
            if (ProductosPedido::modificarPedido($id, $tiempoEstimado,$estado) === true && ProductosPedido::ObtenerSectorPorId($id) === $sector && $tiempoEstimado!=null) {
                $respuesta = json_encode(['mensaje' => 'Producto modificado con exito']);
                $response->getBody()->write($respuesta);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } elseif($tiempoEstimado===null) {
                ProductosPedido::functionActualizarEstado($id,$estado);
                $respuesta = json_encode(['mensaje' => 'Se actualizo el estado con exito']);
                $response->getBody()->write($respuesta);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        } catch (Exception $exception) {
            $respuesta = json_encode(['Error' => 'No se pudo modificar el producto']);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function ListarPorSector($request, $response,$args){
        $sector=$args["sector"];
        ProductosPedido::ListarPorSector($sector);
        $response->getBody()->write("");
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function ListarPorSectorYEstado($request, $response,$args){
        $sector=$args["sector"];
        ProductosPedido::ListarPorSectorYestado($sector,"en preparacion");
        $response->getBody()->write("");
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function ListarPedidosParaServir($request, $response,$args){
        $sector=null;
        ProductosPedido::ListarPorSectorYestado($sector,"listo para servir");
        $response->getBody()->write("");
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    

}