<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LoggerMiddleware implements Middleware
{
    private Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger('app');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '../../../../logs/app.log', Logger::DEBUG));
    }

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $this->logger->info('Request received', [
            'method' => $request->getMethod(),
            'uri' => (string)$request->getUri(),
            'headers' => $request->getHeaders(),
            'body' => $request->getBody()->getContents()
        ]);

        $response = $handler->handle($request);

        $this->logger->info('Response sent', [
            'status' => $response->getStatusCode(),
            'headers' => $response->getHeaders()
        ]);

        return $response;
    }
}
