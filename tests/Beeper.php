<?php

namespace Awesomite\Nano;

/**
 * @internal
 */
class Beeper
{
    private $counter = 0;

    public function beep()
    {
        $this->counter++;
    }

    public function count()
    {
        return $this->counter;
    }
}
