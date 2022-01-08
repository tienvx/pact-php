<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Plugins\ShapeMessage;
use Provider\Service\Calculator;

require __DIR__ . '/../../../../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write(\json_encode(['message' => "Hello, {$name}"]));

    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/goodbye/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write(\json_encode(['message' => "Goodbye, {$name}"]));

    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/calculate', function (Request $request, Response $response) {
    $message = new ShapeMessage();
    $message->mergeFromString($request->getBody()->getContents());
    $reply = (new Calculator())->calculate($message);
    $response->getBody()->write($reply->serializeToString());

    return $response->withHeader('Content-Type', 'application/protobuf;message=AreaResponse');
});

$app->run();
