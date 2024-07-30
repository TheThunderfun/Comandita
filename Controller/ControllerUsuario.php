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
            $tiposValidos = ['bartender', 'cervecero', 'cocinero', 'socio'];
            if(in_array($usuario->tipo, $tiposValidos)){
                $usuario->CrearUsuario();
                $respuesta = json_encode(['mensaje' => 'Usuario creado con exito']);
                $response->getBody()->write($respuesta);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }else{
                throw new Exception("Tipo usuario no valido"); 
            }
             //Usuario::ListarUsuarios();
        } catch (Exception $e) {
            $respuesta = json_encode(['error' => 'Error al crear usuario: ' . $e->getMessage()]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
    public function ModificarUsuario($request, $response, $args){
    $parametros = $request->getParsedBody();

    $usuario = new Usuario();
    $usuario->nombre = $parametros['nombre'];
    $usuario->apellido = $parametros['apellido'];
    $usuario->dni = $parametros['dni'];
    $usuario->tipo = $parametros['tipo'];
    $usuario->clave = password_hash($parametros['clave'], PASSWORD_DEFAULT);

    try {
        $usuario->ModificarUno();
        $respuesta = json_encode(['mensaje' => 'Usuario modificado con exito']);
        $response->getBody()->write($respuesta);
         //Usuario::ListarUsuarios();
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e) {
        $respuesta = json_encode(['error' => 'Error al modificar usuario: ' . $e->getMessage()]);
        $response->getBody()->write($respuesta);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
    }

    public function BajaUsuario($request, $response, $args){
        $dni = $args['dni'];
        $usuario = new Usuario();
        try {
            $usuario->BajaUno($dni);
            $respuesta = json_encode(['mensaje' => 'Usuario eliminado con exito']);
            $response->getBody()->write($respuesta);
             //Usuario::ListarUsuarios();
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $respuesta = json_encode(['error' => 'Error al eliminar usuario: ' . $e->getMessage()]);
            $response->getBody()->write($respuesta);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function Login ($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $dni = $parametros['dni'];        
        $clave = $parametros['clave'];
        
  
        $usuario = new Usuario();
        $usuario->dni = $dni;
        //$usuario->clave = password_hash($clave, PASSWORD_DEFAULT);   
        $hash = Usuario::obtenerPassword($dni);
        //echo $hash;
        if(password_verify($clave,$hash)==false)
        {
          $payload = json_encode(array("mensaje" => "Usuario inexistente."),JSON_PRETTY_PRINT);
        }
        else
        {          
            $user=Usuario::ObtenerUsuarioPorDni($dni);
            $datos = array('nombre'=>$user['nombre'], 'dni'=>$user['dni'],'sector'=>$user['tipo']);          
            
            $token = AuthJWT::CrearToken($datos);
            $payload = json_encode(array('jwt'=>$token));          
        }

       // var_dump($datos);
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}