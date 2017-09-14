<?php

namespace Awesomite\Nano\ArgumentResolver;

use Awesomite\Chariot\RouterInterface;
use Awesomite\Nano\ArgumentResolver\Values\CannotResolveValueException;
use Awesomite\Nano\ArgumentResolver\Values\ClassResolver;
use Awesomite\Nano\ArgumentResolver\Values\ContainerResolver;
use Awesomite\Nano\ArgumentResolver\Values\RequestAttributeResolver;
use Awesomite\Nano\ArgumentResolver\Values\ValueResolverInterface;
use Awesomite\Nano\Nano;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class ArgumentResolver implements ArgumentResolverInterface
{
    /**
     * @var ValueResolverInterface[]
     */
    private $valueResolvers;

    public static function createDefault(Nano $app, Request $request): self
    {
        $classMapping = [
            ContainerInterface::class => $app->getContainer(),
            Request::class            => $request,
            Nano::class               => $app,
            RouterInterface::class    => $app->getRouter(),
        ];

        return (new self())
            ->addResolver(new RequestAttributeResolver($request))
            ->addResolver(new ContainerResolver($app->getContainer()))
            ->addResolver(new ClassResolver($classMapping));
    }

    public function addResolver(ValueResolverInterface $resolver): self
    {
        $this->valueResolvers[] = $resolver;

        return $this;
    }

    public function resolve(\ReflectionFunctionAbstract $function): array
    {
        $result = [];

        foreach ($function->getParameters() as $parameter) {
            foreach ($this->valueResolvers as $resolver) {
                try {
                    $result[] = $resolver->resolve($parameter);
                    continue 2;
                } catch (CannotResolveValueException $exception) {
                }
            }

            throw new ArgumentResolverException(sprintf(
                'Cannot resolve parameter "%s" for %s',
                $parameter->getName(),
                $this->getFunctionName($function)
            ));
        }

        return $result;
    }

    private function getFunctionName(\ReflectionFunctionAbstract $function): string
    {
        $result = $function->getName();

        if ($filename = $function->getFileName()) {
            $result .= '@' . $filename;
        }

        if ($line = $function->getStartLine()) {
            $result .= ':' . $line;

            if ($function->getEndLine() !== $line) {
                $result .= '-' . $function->getEndLine();
            }
        }

        return $result;
    }
}
