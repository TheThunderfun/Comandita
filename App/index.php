<?php

error_reporting(-1);
ini_set('display_errors', 1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require_once '../Controller/ControllerUsuario.php';
require_once '../Controller/ControllerProducto.php';
require_once '../Controller/ControllerPedido.php';
require_once '../Controller/ControllerProductosPedido.php';
require_once '../Controller/ControllerMesa.php';
require_once '../Controller/ControllerCliente.php';
require_once '../Controller/ControllerLog.php';
require_once '../Models/Pdf.php';
require_once '../Middlewares/AuthMiddleware.php';
require_once '../Middlewares/LogMiddleware.php';
require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$app->addBodyParsingMiddleware();

$app->post('/login[/]',\ControllerUsuario::class . ':Login');
$app->post('/pagar[/]',\ControllerPedido::class . ':Pagar');
$app->group('/usuario',function(RouteCollectorProxy $group){
    $group->post('[/]',\ControllerUsuario::class . ':CargarUno');
    $group->post('/modificar',\ControllerUsuario::class . ':ModificarUsuario');
    $group->delete('/borrar/{dni}',\ControllerUsuario::class . ':BajaUsuario');
})->add(\AuthMiddleware::class . ':verificarToken')
->add(\AuthMiddleware::class . ':verificarRolSocio');

$app->group('/producto',function(RouteCollectorProxy $group){
    $group->post('[/]',\ControllerProducto::class . ':CargarUno');
})->add(\AuthMiddleware::class . ':verificarToken')
->add(\AuthMiddleware::class . ':verificarRolSocio');

$app->group('/mesa',function(RouteCollectorProxy $group){
    $group->post('[/]',\ControllerMesa::class . ':CargarUno');
    $group->get('/estado',\ControllerMesa::class . ':ListarMesas');
    $group->post('/cerrar',\ControllerMesa::class . ':ModificarMesa');
})->add(\AuthMiddleware::class . ':verificarToken')
->add(\AuthMiddleware::class . ':verificarRolSocio');

$app->group('/pedido',function(RouteCollectorProxy $group){
    $group->post('[/]',\ControllerPedido::class . ':CargarUno');
    $group->post('/cliente',\ControllerCliente::class . ':CargarUno');
    $group->post('/productos',\ControllerProductosPedido::class . ':CargarUno');
    $group->get('/paraServir',\ControllerProductosPedido::class . ':ListarPedidosParaServir');
    $group->post('/servir',\ControllerPedido::class . ':Servir');
    $group->post('/cobrar',\ControllerPedido::class . ':Cobrar');
    
})->add(\AuthMiddleware::class . ':verificarToken')
    ->add(\AuthMiddleware::class . ':verificarRolMozo');

    $app->get('/encuesta/mejoresComentarios',\ControllerPedido::class . ':TraerMejoresComentarios')->add(\AuthMiddleware::class . ':verificarToken')
    ->add(\AuthMiddleware::class . ':verificarRolSocio');
    $app->get('/encuesta/peoresComentarios',\ControllerPedido::class . ':TraerPeoresComentarios')->add(\AuthMiddleware::class . ':verificarToken')
    ->add(\AuthMiddleware::class . ':verificarRolSocio');
    $app->get('/mesaMasUsada',\ControllerPedido::class . ':MesaMasUsada')->add(\AuthMiddleware::class . ':verificarToken')
    ->add(\AuthMiddleware::class . ':verificarRolSocio');

    $app->get('/productos/menosVendidos',\ControllerProductosPedido::class . ':LoMenosVendido')->add(\AuthMiddleware::class . ':verificarToken')
    ->add(\AuthMiddleware::class . ':verificarRolSocio');

    $app->get('/productos/masVendidos',\ControllerProductosPedido::class . ':LoMasVendido')->add(\AuthMiddleware::class . ':verificarToken')
    ->add(\AuthMiddleware::class . ':verificarRolSocio');

    $app->get('/mesa/masFacturo',\ControllerPedido::class . ':mesaQueMasFacturo')->add(\AuthMiddleware::class . ':verificarToken')
    ->add(\AuthMiddleware::class . ':verificarRolSocio');
    $app->get('/mesa/menosFacturo',\ControllerPedido::class . ':mesaQueMenosFacturo')->add(\AuthMiddleware::class . ':verificarToken')
    ->add(\AuthMiddleware::class . ':verificarRolSocio');
    
    $app->get('/mesa/facturoEntreFechas',\ControllerPedido::class . ':facturacionEntreFechas')->add(\AuthMiddleware::class . ':verificarToken')
    ->add(\AuthMiddleware::class . ':verificarRolSocio');

    $app->get('/mesa/servidoConDemora',\ControllerPedido::class . ':verPedidosConDemora')->add(\AuthMiddleware::class . ':verificarToken')
    ->add(\AuthMiddleware::class . ':verificarRolSocio');
    

    $app->group('/pedido/listar',function(RouteCollectorProxy $group){
        $group->get('/cervecero/{sector}',\ControllerProductosPedido::class . ':ListarPorSector')  ->add(\AuthMiddleware::class . ':verificarToken')
        ->add(\AuthMiddleware::class . ':verificarRolCervecero');
        $group->get('/cocinero/{sector}',\ControllerProductosPedido::class . ':ListarPorSector')->add(\AuthMiddleware::class . ':verificarToken')
        ->add(\AuthMiddleware::class . ':verificarRolCocinero');
        $group->get('/bartender/{sector}',\ControllerProductosPedido::class . ':ListarPorSector')->add(\AuthMiddleware::class . ':verificarToken')
        ->add(\AuthMiddleware::class . ':verificarRolBartender');
    });
    $app->group('/pedido/modificar',function(RouteCollectorProxy $group){
        $group->put('/cervecero/{sector}',\ControllerProductosPedido::class . ':ModificarUno')->add(\AuthMiddleware::class . ':verificarToken')
                                                                                              ->add(\AuthMiddleware::class . ':verificarRolCervecero');
        $group->put('/cocinero/{sector}',\ControllerProductosPedido::class . ':ModificarUno')->add(\AuthMiddleware::class . ':verificarToken')
                                                                                             ->add(\AuthMiddleware::class . ':verificarRolCocinero');
       $group->put('/bartender/{sector}',\ControllerProductosPedido::class . ':ModificarUno')->add(\AuthMiddleware::class . ':verificarToken')
                                                                                             ->add(\AuthMiddleware::class . ':verificarRolBartender');
    });
    $app->group('/cliente/consultar',function(RouteCollectorProxy $group){
        $group->post('[/]',\ControllerPedido::class . ':ConsultarDemora');
    });
    $app->group('/socio',function(RouteCollectorProxy $group){
        $group->get('/consulta/DemoraPedidos',\ControllerPedido::class . ':ListarDemoraPedidos');
    })->add(\AuthMiddleware::class . ':verificarToken')
    ->add(\AuthMiddleware::class . ':verificarRolSocio');

    
    $app->group('/producto/pendiente',function(RouteCollectorProxy $group){
        $group->get('/cervecero/{sector}',\ControllerProductosPedido::class . ':ListarPorSectorYEstado')->add(\AuthMiddleware::class . ':verificarToken')
                                                                                              ->add(\AuthMiddleware::class . ':verificarRolCervecero');
        $group->get('/cocinero/{sector}',\ControllerProductosPedido::class . ':ListarPorSectorYEstado')->add(\AuthMiddleware::class . ':verificarToken')
                                                                                             ->add(\AuthMiddleware::class . ':verificarRolCocinero');
       $group->get('/bartender/{sector}',\ControllerProductosPedido::class . ':ListarPorSectorYEstado')->add(\AuthMiddleware::class . ':verificarToken')
                                                                                             ->add(\AuthMiddleware::class . ':verificarRolBartender');
    });

    $app->group('/socioLogs', function (RouteCollectorProxy $group){  

        $group->get('[/]', \ControllerLog::class . ':CantidadDeOperacionesPorSector');
        $group->get('/empleadoYSector[/]', \ControllerLog::class . ':CantidadDeOperacionesPorEmpleadoYSector');
        $group->post('/empleadoDiasHorarios[/]', \ControllerLog::class . ':EmpleadoDiasYHorarios');
      
      
      })
      ->add(\AuthMiddleware::class . ':verificarToken')
      ->add(\AuthMiddleware::class . ':verificarRolSocio');
      
      
      $app->group('/archivoProductos', function (RouteCollectorProxy $group){
          
          $group->post('/importar-csv[/]', \ControllerProducto::class . ':ImportarTabla');
          $group->get('/guardar[/]', \ControllerProducto::class . ':ExportarTabla'); 
        })
        ->add(\AuthMiddleware::class . ':verificarToken')
        ->add(\AuthMiddleware::class . ':verificarRolSocio');
        
        $app->get('/download', function($request, $response, $args) {
            
            $rutaCompleta = FpdfCreator::CrearPDF();
            
            $filename = $rutaCompleta;  
            
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="'. basename($filename) .'.pdf"');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: max-age=0');
            readfile($filename);
            return true;
        })->add(\AuthMiddleware::class . ':verificarToken')
        ->add(\AuthMiddleware::class . ':verificarRolSocio');
        
        
        $app->add(\LogMiddleware::class);
        $app->run();
        