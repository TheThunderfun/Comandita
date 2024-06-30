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
require_once '../Middlewares/AuthMiddleware.php';
require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$app->addBodyParsingMiddleware();

$app->post('/login[/]',\ControllerUsuario::class . ':Login');

$app->group('/usuario',function(RouteCollectorProxy $group){
    $group->post('[/]',\ControllerUsuario::class . ':CargarUno');
    $group->post('/modificar',\ControllerUsuario::class . ':ModificarUsuario');
    $group->delete('/borrar/{id}',\ControllerUsuario::class . ':BajaUsuario');
})->add(\AuthMiddleware::class . ':verificarToken')
->add(\AuthMiddleware::class . ':verificarRolSocio');

$app->group('/producto',function(RouteCollectorProxy $group){
    $group->post('[/]',\ControllerProducto::class . ':CargarUno');
})->add(\AuthMiddleware::class . ':verificarToken')
->add(\AuthMiddleware::class . ':verificarRolSocio');

$app->group('/mesa',function(RouteCollectorProxy $group){
    $group->post('[/]',\ControllerMesa::class . ':CargarUno');
})->add(\AuthMiddleware::class . ':verificarToken')
->add(\AuthMiddleware::class . ':verificarRolSocio');

$app->group('/pedido',function(RouteCollectorProxy $group){
    $group->post('[/]',\ControllerPedido::class . ':CargarUno');
    $group->post('/cliente',\ControllerCliente::class . ':CargarUno');
    $group->post('/productos',\ControllerProductosPedido::class . ':CargarUno');
})->add(\AuthMiddleware::class . ':verificarToken')
    ->add(\AuthMiddleware::class . ':verificarRolMozo');
    
    
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


$app->run();
