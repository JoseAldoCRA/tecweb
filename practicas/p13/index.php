<?php
declare(strict_types=1);  

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

/*
 * MUY IMPORTANTE:
 * Debe coincidir con lo que pones en el navegador/Postman:
 *   http://localhost/tecweb/practicas/p13/index.php/...
 */
$app->setBasePath('/tecweb/practicas/p13/index.php');

// Errores bonitos
$app->addErrorMiddleware(true, true, true);

/* ---------- RUTAS ---------- */

// GET /  -> raíz
$app->get('', function (Request $request, Response $response): Response {
    $response->getBody()->write('API Slim 4 - Practica 13');
    return $response;
});

/*
 * GET /hola
 * GET /hola/{nombre}
 */
$app->get('/hola[/{nombre}]', function (Request $request, Response $response, array $args): Response {
    $nombre = $args['nombre'] ?? 'Mundo';
    $response->getBody()->write("Hola, $nombre");
    return $response;
});

/*
 * POST /pruebapost
 * Usado con formulario y con Postman (x-www-form-urlencoded)
 */
$app->post('/pruebapost', function (Request $request, Response $response): Response {
    $reqPost = $request->getParsedBody() ?? [];

    $val1 = $reqPost['val1'] ?? '';
    $val2 = $reqPost['val2'] ?? '';

    $respuesta = 'Valores: ' . $val1 . ' ' . $val2;
    $response->getBody()->write($respuesta);
    return $response;
});

/*
 * GET /testjson
 * Devuelve un arreglo JSON como en el video
 */
$app->get('/testjson', function (Request $request, Response $response): Response {
    $data = [];

    $data[0]['nombre']    = 'Jose Aldo';
    $data[0]['apellidos'] = 'Flores Salas';

    $data[1]['nombre']    = 'Osvaldo';
    $data[1]['apellidos'] = 'Capilla Rodriguez';

    $payload = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
