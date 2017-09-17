<?php

namespace Awesomite\Nano;

use Awesomite\Nano\Container\Container;

class SubcontainerTest extends TestBase
{
    public function testGeneral()
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
}
