<?php

namespace Awesomite\Nano;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class TestBase extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->expectOutputString('');
    }
}
