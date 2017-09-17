<?php

namespace Awesomite\Nano;

use Awesomite\ErrorDumper\Views\ViewHtml;
use Awesomite\StackTrace\StackTraceFactory;

/**
 * @internal
 */
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

    public function testStackTrace()
    {
        $app = new Nano();
        $app
            ->enableErrorHandling(false)
            ->enableDebugMode();

        ob_start();
        trigger_error('Test error');
        $content = ob_get_contents();
        ob_end_clean();

        $this->assertContains(ViewHtml::TAG_HTML, $content);

        restore_error_handler();
    }
}
