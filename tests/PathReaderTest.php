<?php

namespace Awesomite\Nano;

use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
class PathReaderTest extends TestBase
{
    public function testInvalidPathReader()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage(sprintf(
            'Callback defined in %s::setPathReader must return string, %s given',
            Nano::class,
            Request::class
        ));

        $app = new Nano();
        $app->setPathReader(function (Request $request) {
            return $request;
        });

        $app->run(Request::create('/'), false);
    }
}
