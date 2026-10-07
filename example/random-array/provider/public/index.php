<?php

use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use React\Http\Message\Response;

require __DIR__ . '/../autoload.php';

$app = new FrameworkX\App();

$app->post('/orders', function (ServerRequestInterface $request) {
    /** @var array{items: list<array<string, mixed>>} $body */
    $body = json_decode((string) $request->getBody(), true);

    // The verifier sends a random number of items (the RandomArray plugin generator is applied
    // to the request); the provider only accepts between 1 and 3
    if (count($body['items']) < 1 || count($body['items']) > 3) {
        return Response::json([
            'success' => false,
            'error' => 'items must contain between 1 and 3 entries',
        ])
            ->withStatus(400);
    }

    $created = array_map(
        fn (): array => [
            'id' => Uuid::uuid4()->toString(),
            'status' => 'pending',
        ],
        array_fill(0, random_int(4, 6), null)
    );

    return Response::json([
        'success' => true,
        'created' => $created,
    ])
        ->withStatus(201);
});

$app->run();
