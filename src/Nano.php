<?php

namespace Awesomite\Nano;

use Awesomite\Chariot\Exceptions\HttpException;
use Awesomite\Chariot\Pattern\PatternRouter;
use Awesomite\Chariot\Pattern\Patterns;
use Awesomite\Chariot\Pattern\PatternsInterface;
use Awesomite\Nano\Container\Container;
use Awesomite\Nano\Traits\ArgumentResolverTrait;
use Awesomite\Nano\Traits\ContainerTrait;
use Awesomite\Nano\Traits\DataTransormerTrait;
use Awesomite\Nano\Traits\ErrorHandlingTrait;
use Awesomite\Nano\Traits\HttpExceptionsTrait;
use Awesomite\Nano\Traits\RoutingTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Nano implements AppInterface
{
    use ArgumentResolverTrait;
    use DataTransormerTrait;
    use ErrorHandlingTrait;
    use ContainerTrait;
    use HttpExceptionsTrait;
    use RoutingTrait;

    public function __construct(PatternsInterface $patterns = null, Container $container = null)
    {
        $this->router = new PatternRouter($patterns ?? Patterns::createDefault());
        $this->container = $container ?? new Container();
    }

    public function run(Request $request = null, bool $autoFlush = true): Response
    {
        $request = $request ?? Request::createFromGlobals();

        try {
            $route = $this->router->match($request->getMethod(), $request->getBaseUrl() . $request->getPathInfo());
            $handler = $this->httpHandlers[$route->getHandler()];
            $request->attributes->add($route->getParams());
            $this->defaultHttpStatusCode = Response::HTTP_OK;
        } catch (HttpException $exception) {
            $handler = $this->getHttpExceptionHandler($exception);
            $this->defaultHttpStatusCode = $exception->getCode();
        }

        $arguments = $this->resolveArguments($request, $handler);
        $data = call_user_func_array($handler, $arguments);

        $result = $this->transformToResponse($data);
        if ($autoFlush) {
            $result->send();
        }

        return $result;
    }
}
