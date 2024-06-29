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
require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$app->addBodyParsingMiddleware();

$app->group('/usuario',function(RouteCollectorProxy $group){
    $group->post('[/]',\ControllerUsuario::class . ':CargarUno');
});

$app->group('/producto',function(RouteCollectorProxy $group){
    $group->post('[/]',\ControllerProducto::class . ':CargarUno');
});

$app->group('/mesa',function(RouteCollectorProxy $group){
    $group->post('[/]',\ControllerMesa::class . ':CargarUno');
});

$app->group('/pedido',function(RouteCollectorProxy $group){
    $group->post('[/]',\ControllerPedido::class . ':CargarUno');
    $group->post('/cliente',\ControllerCliente::class . ':CargarUno');
    $group->post('/productos',\ControllerProductosPedido::class . ':CargarUno');
});



$app->run();
