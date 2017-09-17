<?php

namespace Awesomite\Nano\Traits;

use Awesomite\Chariot\Exceptions\HttpException;
use Awesomite\Chariot\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
trait HttpExceptionsTrait
{
    private $httpExceptionHandlers = [];

    public function on404(callable $callable): self
    {
        $this->httpExceptionHandlers[HttpException::HTTP_NOT_FOUND] = $callable;

        return $this;
    }

    public function on405(callable $callable): self
    {
        $this->httpExceptionHandlers[HttpException::HTTP_METHOD_NOT_ALLOWED] = $callable;

        return $this;
    }

    private function getHttpExceptionHandler(HttpException $exception): callable
    {
        if ($handler = ($this->httpExceptionHandlers[$exception->getCode()] ?? null)) {
            return $handler;
        }

        switch ($exception->getCode()) {
            case HttpException::HTTP_METHOD_NOT_ALLOWED:
                return function (Request $request, RouterInterface $router) {
                    $path = $this->readPath($request);
                    $allows = implode(', ', $router->getAllowedMethods($path));
                    $headers = [
                        'Allows'       => $allows,
                        'Content-Type' => 'text/plain',
                    ];
                    $body
                        = <<<BODY
Method not allowed: {$request->getMethod()} {$request->getUri()}
Allows: {$allows}
BODY;

                    return new Response($body, Response::HTTP_METHOD_NOT_ALLOWED, $headers);
                };

            default:
            case HttpException::HTTP_NOT_FOUND:
                return function (Request $request) {
                    $content = sprintf('Not found: %s %s', $request->getMethod(), $request->getUri());

                    return new Response($content, Response::HTTP_NOT_FOUND, ['Content-Type' => 'text/plain']);
                };
        }
    }
}
