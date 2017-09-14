<?php

namespace Awesomite\Nano;

use Awesomite\Chariot\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
class HttpErrorsTest extends TestBase
{
    public function test404()
    {
        $app = new Nano();
        $beeper = new Beeper();
        $app->on404(function () use ($beeper) {
            $beeper->beep();

            return 'Custom error 404';
        });
        $this->assertSame(0, $beeper->count());
        $response = $app->run(Request::create('http://home.local/404'), false);
        $this->assertSame('Custom error 404', $response->getContent());
        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertSame(1, $beeper->count());
    }

    public function test405()
    {
        $app = new Nano();
        $beeper = new Beeper();
        $app->get('home', '/', function () {
            return 'Homepage';
        });
        $app->on405(function (RouterInterface $router, Request $request) use ($beeper) {
            $beeper->beep();
            $path = $request->getBasePath() . $request->getPathInfo();
            $headers = [
                'Allows' => implode(',', $router->getAllowedMethods($path)),
            ];

            return new Response('Custom error 405', Response::HTTP_METHOD_NOT_ALLOWED, $headers);
        });
        $this->assertSame(0, $beeper->count());
        $response = $app->run(Request::create('http://home.local/', 'POST'), false);
        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $response->getStatusCode());
        $this->assertSame('Custom error 405', $response->getContent());
        $this->assertTrue($response->headers->has('Allows'));
    }
}
