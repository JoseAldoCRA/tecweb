<?php
// backend/index.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;

// Autoload de Composer
require __DIR__ . '/vendor/autoload.php';

// Tus clases propias (ahora dentro de backend/myapi)
require __DIR__ . '/myapi/DataBase.php';
require __DIR__ . '/myapi/Create/Create.php';
require __DIR__ . '/myapi/Read/Read.php';
require __DIR__ . '/myapi/Update/Update.php';
require __DIR__ . '/myapi/Delete/Delete.php';

use TECWEB\MYAPI\Create\Create;
use TECWEB\MYAPI\Read\Read;
use TECWEB\MYAPI\Update\Update;
use TECWEB\MYAPI\Delete\Delete;

$app = AppFactory::create();
$app->setBasePath('/tecweb/practicas/a09/product_app/backend');

// Middleware para leer JSON / form-data
$app->addBodyParsingMiddleware();

// Routing & errores
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

// CORS (Slim 4)
$app->add(function (Request $request, RequestHandler $handler): Response {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Accept')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// GET /backend/  y /backend  → prueba rápida
$app->get('', function (Request $request, Response $response): Response {
    $payload = json_encode([
        'status'  => 'ok',
        'message' => 'API ProductApp funcionando'
    ], JSON_UNESCAPED_UNICODE);

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/', function (Request $request, Response $response): Response {
    $payload = json_encode([
        'status'  => 'ok',
        'message' => 'API ProductApp funcionando'
    ], JSON_UNESCAPED_UNICODE);

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

/**
 * GET /backend/product/{id}
 */
$app->get('/product/{id}', function (Request $request, Response $response, array $args): Response {
    $id = $args['id'];

    $read = new Read();
    $result = $read->single($id);

    if (!is_string($result)) {
        $result = json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    $response->getBody()->write($result);
    return $response->withHeader('Content-Type', 'application/json');
});

/**
 * GET /backend/products
 */
$app->get('/products', function (Request $request, Response $response): Response {
    $read = new Read();
    $result = $read->list();

    if (!is_string($result)) {
        $result = json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    $response->getBody()->write($result);
    return $response->withHeader('Content-Type', 'application/json');
});

/**
 * GET /backend/products/{search}
 */
// --- Buscar productos por texto ---
$app->get('/products/{search}', function (Request $request, Response $response, array $args): Response {
    $search = $args['search'];
    $read = new Read();
    $data = $read->search($search);

    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
    return $response->withHeader('Content-Type', 'application/json');
});


/**
 * POST /backend/product
 */
$app->post('/product', function (Request $request, Response $response) {

    // Si tienes BodyParsingMiddleware activo, esto ya viene parseado
    $data = $request->getParsedBody();
    if (!is_array($data)) {
        // Si estás enviando JSON crudo:
        $data = json_decode($request->getBody()->getContents(), true) ?? [];
    }

    $object = (object) $data;

    $create = new Create();
    $result = $create->add($object);   // ← ahora $result es un ARREGLO

    $response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
});

/**
 * PUT /backend/product
 */
$app->put('/product', function (Request $request, Response $response): Response {
    $data = $request->getParsedBody();
    $object = (object) $data;

    $update = new Update();
    $result = $update->edit($object);

    if (!is_string($result)) {
        $result = json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    $response->getBody()->write($result);
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});

/**
 * DELETE /backend/product
 */
$app->delete('/product', function (Request $request, Response $response): Response {
    $data = $request->getParsedBody();
    $id = $data['id'] ?? null;

    $delete = new Delete();
    $result = $delete->delete($id);

    if (!is_string($result)) {
        $result = json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    $response->getBody()->write($result);
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});

$app->run();
