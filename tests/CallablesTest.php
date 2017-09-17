<?php

namespace Awesomite\Nano;

use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
class CallablesTest extends TestBase
{
    public function testGeneral()
    {
        $app = new Nano();
        $app->getContainer()->set('beeper', $beeper = new Beeper());

        $app->get('/invokable', $this->createInvokable());
        $app->get('/static', sprintf('%s::staticMethod', static::class));
        $app->get('/dynamic', [$this, 'staticMethod']);
        $app->get('/anonymous', function (Beeper $beeper) {
            $beeper->beep();
        });
        $app->get('/function', __NAMESPACE__ . '\\beep');

        $i = 0;
        foreach (['/invokable', '/static', '/dynamic', '/anonymous', '/function'] as $path) {
            $response = $app->run(Request::create('http://home.local' . $path), false);
            $response->getContent();
            $this->assertSame(++$i, $beeper->count(), $path);
        }
    }

    public static function staticMethod(Beeper $beeper)
    {
        $beeper->beep();
    }

    private function createInvokable(): callable
    {
        return new class {
            public function __invoke(Beeper $beeper)
            {
                $beeper->beep();
            }
        };
    }
}

function beep(Beeper $beeper)
{
    $beeper->beep();
}
