<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

include_once '../Middlewares/AuthJWT.php';

Class AuthMiddleware{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getQueryParams();

        $sector = $parametros['sector'];

        if ($sector === 'admin') {
            $response = $handler->handle($request);
        } 
        else 
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'No sos Admin'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function verificarToken(Request $request, RequestHandler $handler) : Response
    {
        $header = $request->getHeaderLine('Authorization');
        if($header)
        {
            $token = trim(explode("Bearer", $header)[1]);
            try{                       
                AuthJWT::VerificarToken($token);
                $response = $handler->handle($request);
            }
            catch (Exception $e)
            {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN !!',"excepcion"=>$e));
                $response->getBody()->write($payload);
            }
        }
        else 
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: No hay seteada una autorizacion'));
            $response->getBody()->write( $payload);
        }
        
        return $response->withHeader('Content-Type','application/json');
    }

    public static function verificarRolSocio(Request $request, RequestHandler $handler) : Response
    {
        $header = $request->getHeaderLine('Authorization');
      
        $token = trim(explode("Bearer", $header)[1]);

        try{
            
            AuthJWT::VerificarToken($token);
            
            $data = AuthJWT::ObtenerData($token);
      
            if ($data->sector === 'socio')
            {   
                //aca propaga el middleware a otro
                $request->datosToken= $data;

                $response = $handler->handle($request);
            } 
            else
            {
                throw new Exception();
            }          
        }
        catch (Exception $e)
        {   var_dump($token);
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Usuario no autorizado'));
            $response->getBody()->write( $payload);
        }
             
        return $response->withHeader('Content-Type','application/json');
    }

    public static function verificarRolMozo(Request $request, RequestHandler $handler) : Response
    {
        $header = $request->getHeaderLine('Authorization');
      
        $token = trim(explode("Bearer", $header)[1]);
        try{
            AuthJWT::VerificarToken($token);

            
            $data = AuthJWT::ObtenerData($token);
      
            if ($data->sector === 'mozo')
            {   
                //aca propaga el middleware a otro
                $request->datosToken= $data;

                $response = $handler->handle($request);
            } 
            else
            {
                throw new Exception();
            }          
        }
        catch (Exception $e)
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Usuario no autorizado'));
            $response->getBody()->write( $payload);
        }
             
        return $response->withHeader('Content-Type','application/json');
    }

    public static function verificarRolCocinero(Request $request, RequestHandler $handler) : Response
    {
        $header = $request->getHeaderLine('Authorization');
      
        $token = trim(explode("Bearer", $header)[1]);
        try{
            AuthJWT::VerificarToken($token);

            
            $data = AuthJWT::ObtenerData($token);
      
            if ($data->sector === 'cocinero')
            {   
                //aca propaga el middleware a otro
                $request->datosToken= $data;

                $response = $handler->handle($request);
            } 
            else
            {
                throw new Exception();
            }          
        }
        catch (Exception $e)
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Usuario no autorizado'));
            $response->getBody()->write( $payload);
        }
             
        return $response->withHeader('Content-Type','application/json');
    }


    public static function verificarRolCervecero(Request $request, RequestHandler $handler) : Response
    {
        $header = $request->getHeaderLine('Authorization');
      
        $token = trim(explode("Bearer", $header)[1]);
        try{
            AuthJWT::VerificarToken($token);

            
            $data = AuthJWT::ObtenerData($token);
      
            if ($data->sector === 'cervecero')
            {   
                //aca propaga el middleware a otro
                $request->datosToken= $data;

                $response = $handler->handle($request);
            } 
            else
            {
                throw new Exception();
            }          
        }
        catch (Exception $e)
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Usuario no autorizado'));
            $response->getBody()->write( $payload);
        }
             
        return $response->withHeader('Content-Type','application/json');
    }

    public static function verificarRolBartender(Request $request, RequestHandler $handler) : Response
    {
        $header = $request->getHeaderLine('Authorization');
      
        $token = trim(explode("Bearer", $header)[1]);
        try{
            //var_dump("hola");
            AuthJWT::VerificarToken($token);
            
            
            $data = AuthJWT::ObtenerData($token);
            if ($data->sector === 'bartender')
            {   
                //aca propaga el middleware a otro
                $request->datosToken= $data;
                
                $response = $handler->handle($request);
            } 
            else
            {
                throw new Exception();
            }          
        }
        catch (Exception $e)
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Usuario no autorizado'));
            $response->getBody()->write( $payload);
        }
             
        return $response->withHeader('Content-Type','application/json');
    }

}