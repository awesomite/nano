<?php

namespace Awesomite\Nano;

use Awesomite\Nano\Container\Container;
use Awesomite\Nano\Container\NotFoundException;

class ContainerTest extends TestBase
{
    public function testSubContainer()
    {
        $container = new Container();
        $container->includeContainer($subContainer = new Container());

        $subContainer->set('organization', 'awesomite');
        $beeper = new Beeper();
        $subContainer->setLazy('name', function () use ($beeper) {
            return 'nano';
        });

        $this->assertTrue($container->has('organization'));
        $this->assertTrue($container->has('name'));

        $this->assertSame('awesomite', $container->get('organization'));

        $this->assertSame(0, $beeper->count());
        for ($i = 0; $i < 0; $i++) {
            $this->assertSame('nano', $container->get('name'));
            $this->assertSame(1, $beeper->count());
        }
    }

    public function testNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Not found "data"');

        $container = new Container();
        $container->get('data');
    }

    public function testRegisterLazy()
    {
        $container = new Container();
        $beeper = new Beeper();
        $container->setLazy('lazy', function () use ($beeper) {
            $beeper->beep();

            return 'lazy data';
        });

        $this->assertTrue($container->has('lazy'));
        $this->assertSame(0, $beeper->count());

        for ($i = 0; $i < 2; $i++) {
            $this->assertSame('lazy data', $container->get('lazy'));
            $this->assertSame(1, $beeper->count());
        }
    }

    public function testGeneral()
    {
        $container = new Container();
        $this->assertFalse($container->has('key'));
        $container->set('key', 'value');
        $this->assertTrue($container->has('key'));
        $this->assertSame('value', $container->get('key'));
    }
}
