<?php

namespace Awesomite\Nano\Traits;

use Awesomite\Nano\ArgumentResolver\ArgumentResolver;
use Awesomite\Nano\ArgumentResolver\ArgumentResolverInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
trait ArgumentResolverTrait
{
    private function resolveArguments(Request $request, callable $callable): array
    {
        return $this->createArgumentResolver($request)->resolve(
            $this->createReflectionCallable($callable)
        );
    }

    private function createArgumentResolver(Request $request): ArgumentResolverInterface
    {
        return ArgumentResolver::createDefault($this, $request);
    }

    private function createReflectionCallable(callable $callable): \ReflectionFunctionAbstract
    {
        if (is_object($callable)) {
            if ($callable instanceof \Closure) {
                return new \ReflectionFunction($callable);
            }

            return new \ReflectionMethod($callable, '__invoke');
        }

        if (is_string($callable)) {
            if (false === strpos($callable, '::')) {
                return new \ReflectionFunction($callable);
            }

            $callable = explode('::', $callable, 2);
        }

        return new \ReflectionMethod(...$callable);
    }
}
