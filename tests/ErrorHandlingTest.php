<?php

namespace Awesomite\Nano;

class ErrorHandlingTest extends TestBase
{
    public function testGeneral()
    {
        $app = new Nano();
        $app->enableErrorHandling(false);
        $beeper = new Beeper();
        $app->onError(function (\Throwable $throwable) use ($beeper) {
            $beeper->beep();
        });

        $this->assertSame(0, $beeper->count());
        @trigger_error('Hello');
        $this->assertSame(0, $beeper->count());
        trigger_error('Hello');
        $this->assertSame(1, $beeper->count());

        restore_error_handler();
    }
}

