<?php

namespace Awesomite\Nano;

use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
class InjectionsTest extends TestBase
{
    public function testInjections()
    {
        $app = new Nano();
        $app->getContainer()
            ->set('organization', 'awesomite')
            ->set('repository', 'nano');

        $app->get(
            '/user/{{ userName }}',
            function (string $userName, string $organization, string $repository) {
                return sprintf('User %s, organization %s, repository %s', $userName, $organization, $repository);
            }
        );

        $response = $app->run(Request::create('https://home.local/user/bkrukowski'), false);
        $this->assertSame('User bkrukowski, organization awesomite, repository nano', $response->getContent());
    }

    public function testLazyInjection()
    {
        $app = new Nano();
        $beeper = new Beeper();
        $app->getContainer()->setLazy('lazyName', function () use ($beeper) {
            $beeper->beep();

            return 'lazyValue';
        });

        $app->get('/', function ($lazyName) {
            return 'home';
        });
        $this->assertSame(0, $beeper->count());
        $app->run(Request::create('https://home.local/'), false);
        $this->assertSame(1, $beeper->count());
        $this->assertSame('lazyValue', $app->getContainer()->get('lazyName'));
        $this->assertSame(1, $beeper->count());
    }
}
