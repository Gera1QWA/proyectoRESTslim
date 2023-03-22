<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__ . '../vendor/autoload.php';

AppFactory::setSlimHttpDecoratorsAutomaticDetection(false);
ServerRequestCreatorFactory::setSlimHttpDecoratorsAutomaticDetection(false);

// Instantiate App
$app = AppFactory::create();

$app->setBasePath("/webservices/proyectoRESTslim/holamundo.php");

$app->addBodyParsingMiddleware();

$app->addRoutingMiddleware();
// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add routes
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('hola mundo slim');
    return $response;
});

$app->get('/hola/{name}', function (Request $request, Response $response, $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});
$app->get('/edad/{edad}', function (Request $request, Response $response, $args) {
    $edad = $args['edad'];
    $response->getBody()->write("Hello, $edad");
    return $response;
});

$app->get('/testjson', function (Request $request, Response $response, $args) {
        $json[0]["nombre"] = "Gerardo";
        $json[0]["apellido"] = "Camarillo Galeno";
        $json[1]["nombre"] = "Herlinda";
        $json[1]["apellido"] = "Camarillo Galeno";
        $json[2]["nombre"] = "Raúlss";
        $json[2]["apellido"] = "Camarillo Galeno";
        $response->getBody()->write(json_encode($json,JSON_PRETTY_PRINT));
        return $response;

});
$app->post("/pruebapost", function (Request $request, Response $response, $args) {
    $rsqPost = $request->getParsedBody();
    $val1 = $rsqPost["val1"];
    $val2 = $rsqPost["val2"];
    $response->getBody()->write("valores:" .$val1 . " " .$val2);
    return $response;
});

$app->run();

?>