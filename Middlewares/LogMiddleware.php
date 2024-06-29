<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Response;


require_once '../Models/Log.php';

class LogMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {    
        $log = new Log();
        $log->dni = null;
        $log->sector = null;
        

        $header = $request->getHeaderLine('Authorization');   
                
        if(!empty($header)){            
            $token = trim(explode("Bearer", $header)[1]);
            $data = AuthJWT::ObtenerData($token);
                           
            $log->dni = $data->dni;
            $log->sector = $data->sector;                                    
        }        

        $log->url = $request->getUri()->getPath();
        $log->metodo = $request->getMethod();
        $log->CargarUno();

        $response = $handler->handle($request);
        return $response;
    }   
}