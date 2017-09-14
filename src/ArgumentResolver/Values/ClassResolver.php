<?php

namespace Awesomite\Nano\ArgumentResolver\Values;

class ClassResolver implements ValueResolverInterface
{
    private $mapping;

    public function __construct(array $mapping)
    {
        $this->mapping = $mapping;
    }

    public function resolve(\ReflectionParameter $parameter)
    {
        $requiredClass = $parameter->getClass();
        if (!$requiredClass) {
            throw new CannotResolveValueException();
        }

        foreach ($this->mapping as $class => $obj) {
            if (
                $requiredClass->getName() === $class
                || (new \ReflectionClass($class))->isSubclassOf($parameter->getClass()->getName())
            ) {
                return $obj;
            }
        }

        throw new CannotResolveValueException();
    }
}
