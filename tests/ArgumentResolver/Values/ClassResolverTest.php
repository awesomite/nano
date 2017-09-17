<?php

namespace Awesomite\StackTrace\Arguments\Values;

use Awesomite\Nano\ArgumentResolver\Values\CannotResolveValueException;
use Awesomite\Nano\ArgumentResolver\Values\ClassResolver;
use Awesomite\Nano\TestBase;

/**
 * @internal
 */
class ClassResolverTest extends TestBase
{
    public function testResolveClass()
    {
        $resolver = new ClassResolver([
            static::class => $this,
        ]);
        $parameter = $this->getReflectionParameter(1);
        $this->assertSame($this, $resolver->resolve($parameter));
    }

    public function testResolveSubclass()
    {
        $resolver = new ClassResolver([
            static::class => $this,
        ]);
        $parameter = $this->getReflectionParameter(0);
        $this->assertSame($this, $resolver->resolve($parameter));
    }

    public function testCannotResolve()
    {
        $this->expectException(CannotResolveValueException::class);

        $resolver = new ClassResolver([]);
        $resolver->resolve($this->getReflectionParameter(0));
    }

    private function getReflectionParameter(int $index): \ReflectionParameter
    {
        return (new \ReflectionMethod($this, 'sampleParameters'))->getParameters()[$index];
    }

    private function sampleParameters(TestBase $parent, self $self)
    {
    }
}
