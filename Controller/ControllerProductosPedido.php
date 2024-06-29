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

        try {
            if(ProductosPedido::BuscarProductoPorNombre($productos->producto)!=null){
                $productos->CargarUno();
                $tiempoEstimado=Pedido::VerTiempoEstimadoPedido($productos->pedido_id);
                Pedido::ActulizarTiempoEstimado($tiempoEstimado,$productos->pedido_id);
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

}