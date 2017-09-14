<?php

namespace Awesomite\Nano\ArgumentResolver\Values;

use Symfony\Component\HttpFoundation\Request;

class RequestAttributeResolver implements ValueResolverInterface
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function resolve(\ReflectionParameter $parameter)
    {
        $name = $parameter->getName();

        if ($this->request->attributes->has($name)) {
            return $this->request->attributes->get($name);
        }

        throw new CannotResolveValueException();
    }
}
