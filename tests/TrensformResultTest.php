<?php

namespace Awesomite\Nano;

use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
class TrensformResultTest extends TestBase
{
    public function testJson()
    {
        $app = new Nano();
        $app->get('jsonSample', '/json', function () {
            return [1, 2, 3];
        });
        $result = $app->run(Request::create('http://home.local/json'), false);
        $this->assertContains('application/json', $result->headers->get('content-type'));
    }
}
