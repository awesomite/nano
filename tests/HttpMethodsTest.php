<?php

namespace Awesomite\Nano;

use Symfony\Component\HttpFoundation\Request;

class HttpMethodsTest extends TestBase
{
    public function testGeneral()
    {
        $app = new Nano();
        $app->get('get', '/', function () {
            return 'GET';
        });
        $app->post('post', '/', function () {
            return 'POST';
        });
        $app->patch('patch', '/', function () {
            return 'PATCH';
        });
        $app->put('put', '/', function () {
            return 'PUT';
        });
        $app->delete('delete', '/', function () {
            return 'DELETE';
        });
        $app->any('any', '/any', function () {
            return 'ANY';
        });

        foreach (['GET', 'POST', 'PATCH', 'PUT', 'DELETE'] as $method) {
            $response = $app->run(Request::create('http://home.local/', $method), false);
            $this->assertSame($method, $response->getContent());

            $response2 = $app->run(Request::create('http://home.local/any', $method), false);
            $this->assertSame('ANY', $response2->getContent());
        }
    }
}
