<?php

namespace Awesomite\Nano\Traits;

use Awesomite\Chariot\HttpMethods;
use Awesomite\Chariot\Pattern\PatternRouter;
use Awesomite\Chariot\RouterInterface;
use Awesomite\Nano\Nano;

/**
 * @internal
 *
 * @see https://github.com/awesomite/chariot
 */
trait RoutingTrait
{
    /**
     * @var PatternRouter
     */
    private $router;

    private $httpHandlers = [];

    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * @param string       $name
     * @param string|array $method
     * @param string       $path
     * @param callable     $handler
     *
     * @return Nano
     */
    public function route(string $name, $method, string $path, callable $handler): self
    {
        $this->router->addRoute($method, $path, $name);
        $this->httpHandlers[$name] = $handler;

        return $this;
    }

    public function get(string $name, string $path, callable $handler): self
    {
        return $this->route($name, HttpMethods::METHOD_GET, $path, $handler);
    }

    public function post(string $name, string $path, callable $handler): self
    {
        return $this->route($name, HttpMethods::METHOD_POST, $path, $handler);
    }

    public function patch(string $name, string $path, callable $handler): self
    {
        return $this->route($name, HttpMethods::METHOD_PATCH, $path, $handler);
    }

    public function put(string $name, string $path, callable $handler): self
    {
        return $this->route($name, HttpMethods::METHOD_PUT, $path, $handler);
    }

    public function delete(string $name, string $path, callable $handler): self
    {
        return $this->route($name, HttpMethods::METHOD_DELETE, $path, $handler);
    }

    public function any(string $name, string $path, callable $handler): self
    {
        return $this->route($name, HttpMethods::METHOD_ANY, $path, $handler);
    }
}
