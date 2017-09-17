<?php

namespace Awesomite\Nano;

use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
class HostRoutingTest extends TestBase
{
    public function testGeneral()
    {
        $app = new Nano();
        $beeper = new Beeper();
        $app->get('{{ user [^.]+ }}.home.local/', function (string $user) use ($beeper) {
            $beeper->beep();

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

    /**
     * @dataProvider providerInvalidPath
     *
     * @param $path
     */
    public function testInvalidPath($path)
    {
        $this->expectException(\InvalidArgumentException::class);

        $app = new Nano();
        $app->get($path, function () {
        });
    }

    public function providerInvalidPath()
    {
        return [
            [['/', 'home', 'redundant']],
            [['a' => '/', 'b' => 'home']],
            [false],
            [new \stdClass()],
        ];
    }
}
