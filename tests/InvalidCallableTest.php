<?php

namespace Awesomite\Nano;

use Awesomite\Nano\ArgumentResolver\ArgumentResolverException;
use Symfony\Component\HttpFoundation\Request;

class InvalidCallableTest extends TestBase
{
    public function testGeneral()
    {
        $app = new Nano();
        $app->get('/', function ($redis) {
            return 'Hello!';
        });
        $this->expectException(ArgumentResolverException::class);
        $app->run(Request::create('http://home.local/'), false);
    }
}
