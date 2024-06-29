<?php
include_once '../Models/Usuario.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Factory\AppFactory;
Class ControllerUsuario{
    public function CargarUno($request, $response, $args) {
        $datos = $request->getParsedBody();
    
        $usuario = new Usuario();
        $usuario->nombre = $datos['nombre'];
        $usuario->apellido = $datos['apellido'];
        $usuario->tipo =  strtolower($datos['tipo']);
        $usuario->fechaAlta = date('Y-m-d');
        $usuario->dni=$datos['dni'];
        $usuario->clave = password_hash($datos['clave'], PASSWORD_DEFAULT);
    
        try {
            $usuario->CrearUsuario();
            $respuesta = json_encode(['mensaje' => 'Usuario creado con exito']);
            $response->getBody()->write($respuesta);
             //Usuario::ListarUsuarios();
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $respuesta = json_encode(['error' => 'Error al crear usuario: ' . $e->getMessage()]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}