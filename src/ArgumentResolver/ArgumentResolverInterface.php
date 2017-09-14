<?php

namespace Awesomite\Nano\ArgumentResolver;

interface ArgumentResolverInterface
{
    /**
     * @param \ReflectionFunctionAbstract $function
     *
     * @return array
     *
     * @throws ArgumentResolverException
     */
    public function resolve(\ReflectionFunctionAbstract $function): array;
}
