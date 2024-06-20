<?php

error_reporting(-1);
ini_set('display_errors', 1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require_once './controller/UsuarioController.php';
require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$app->get('/name', function ($request, $response, array $args) {
		$response->getBody()->write("Funciona!");
return $response;
});

$app->get("/test",function ($request, $response, array $args)
{
    $params=$request->getQueryParams();

    $response->getBody()->write(json_encode($params));
    return $response;

});
$app->run();
