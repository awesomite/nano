<?php

namespace Awesomite\Nano;

/**
 * @internal
 *
 * @see https://github.com/awesomite/chariot
 */
class GeneratingLinksTest extends TestBase
{
    public function testGenerate()
    {
        $app = new Nano();
        $app->get('home', '/', function () {
        });
        $app->get('userpage', '/user-{{ name }}', function () {
        });
        $app->get('showCategory', '/category/{{ id :int }}', function () {
        });

        $router = $app->getRouter();
        $this->assertEquals('/', $router->linkTo('home'));
        $this->assertEquals('/user-jane', $router->linkTo('userpage')->withParam('name', 'jane'));
        $this->assertEquals('/category/500', $router->linkTo('showCategory')->withParam('id', 500));
    }
}
