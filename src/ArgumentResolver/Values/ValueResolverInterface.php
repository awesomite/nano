<?php

namespace Awesomite\Nano\ArgumentResolver\Values;

interface ValueResolverInterface
{
    /**
     * @param \ReflectionParameter $parameter
     *
     * @return mixed
     *
     * @throws CannotResolveValueException
     */
    public function resolve(\ReflectionParameter $parameter);
}
