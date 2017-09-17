<?php

namespace Awesomite\Nano;

use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
class DomainRoutingTest extends TestBase
{
    public function testGeneral()
    {
        $app = new Nano();
        $beeper = new Beeper();
        $app->get('userpage', '{{ user [^.]+ }}.home.local/', function (string $user) use ($beeper) {
            $beeper->count();

            return $user;
        });
        $app->setPathReader(function (Request $request) {
            return $request->getHost() . $request->getBaseUrl() . $request->getPathInfo();
        });

        $this->assertSame(0, $beeper->count());
        $response = $app->run(Request::create('http://jane.home.local/'), false);
        $this->assertSame(1, $beeper->count());
        $this->assertSame('jane', $response->getContent());
    }
}
