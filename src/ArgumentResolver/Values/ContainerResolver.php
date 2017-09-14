<?php

namespace Awesomite\Nano\ArgumentResolver\Values;

use Psr\Container\ContainerInterface;

class ContainerResolver implements ValueResolverInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve(\ReflectionParameter $parameter)
    {
        $name = $parameter->getName();

        if ($this->container->has($name)) {
            return $this->container->get($name);
        }

        throw new CannotResolveValueException();
    }
}
