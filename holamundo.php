<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__ . '../vendor/autoload.php';
require_once __DIR__.'/ClaseSw.php';
use ClaseSw\DB;


AppFactory::setSlimHttpDecoratorsAutomaticDetection(false);
ServerRequestCreatorFactory::setSlimHttpDecoratorsAutomaticDetection(false);

// Instantiate App
$app = AppFactory::create();
$res = new DB('serviciosweb-2039-default-rtdb');



$app->setBasePath("/webservices/proyectoRESTslim/holamundo.php");

$app->addBodyParsingMiddleware();

$app->addRoutingMiddleware();
// Add error middleware
$app->addErrorMiddleware(true, true, true);
$resp = array(
    'code'    => 999,
    'message' => '',
    'data'    => '',
    'status'  => 'error'
);

// Add routes
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('hola mundo slim');
    return $response;
});

$app->get('/productos/{categoria}', function (Request $request, Response $response, $args) {
    global $res;
    global $resp;
    $categoria = $args['categoria'];
    $headers = $request->getHeaderLine('user');  
    $headers2 = $request->getHeaderLine('pass');
     //$response->getBody()->write("Hello, $headers");
    // $response->getBody()->write("Hello, $categoria");
    
    if($request->hasHeader('user')){
        if($res->isUser($headers)){
            if($res->obtainPass($headers) === md5($headers2)){
                if($res->isCategoryDB($categoria)){
                    $resp['code'] = 200;
                    $resp['message'] = $res->obtainMessage('200');
                    $resp['status'] = 'success';
                    $resp['data'] = $res->obtainProduc($categoria);
                    $json = json_encode($resp);
                    $response->getBody()->write($json);

                }else{
                    $resp['code'] = 300;
                    $resp['message'] = $res->obtainMessage('300');
                    $json = json_encode($resp,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    $response->getBody()->write($json);
                }
                
            
            }else{
                $resp['code'] = 501;
                $resp['message'] =   $res->obtainMessage('501');
                $json = json_encode($resp);
                $response->getBody()->write($json);
            }

        }else{
            $resp['code'] = 500;
            $resp['message'] =   $res->obtainMessage('500');
            $json = json_encode($resp);
            $response->getBody()->write($json);
        }

    }else{
        $resp['code'] = 500;
        $resp['message'] =  $res->obtainMessage('500');
        $json = json_encode($resp);
        $response->getBody()->write($json);
    }

    return $response;
    
  
});


$app->run();

?>