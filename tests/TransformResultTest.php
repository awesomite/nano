<?php

namespace Awesomite\Nano;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
class TransformResultTest extends TestBase
{
    public function testJson()
    {
        $app = new Nano();
        $app->get('/json', function () {
            return [1, 2, 3];
        });
        $app->get('/stringable', function () {
            return new class
            {
                public function __toString()
                {
                    return 'some text';
                }
            };
        });
        $app->get('/html', function () {
            return '<h1>Welcome</h1>';
        });

        $jsonResult = $app->run(Request::create('http://home.local/json'), false);
        $this->assertContentTypeJson($jsonResult);

        $stringableResult = $app->run(Request::create('http://home.local/stringable'), false);
        $this->assertContentTypeHtml($stringableResult);
        $this->assertResponseBody('some text', $stringableResult);

        $htmlResult = $app->run(Request::create('http://home.local/html'), false);
        $this->assertContentTypeHtml($htmlResult);
        $this->assertResponseBody('<h1>Welcome</h1>', $htmlResult);
    }

    private function assertContentTypeJson(Response $response)
    {
        $this->assertContains('application/json', $response->headers->get('content-type'));
    }

    private function assertContentTypeHtml(Response $response)
    {
        if (!$response->headers->has('content-type')) {
            $this->assertTrue(true);

            return;
        }
        $this->assertContains('text/html', $response->headers->get('content-type'));
    }

    private function assertResponseBody(string $expected, Response $response)
    {
        ob_start();
        $response->send();
        $data = ob_get_contents();
        ob_end_clean();

        $this->assertSame($expected, $data);
    }
}
