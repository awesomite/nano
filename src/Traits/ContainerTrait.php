<?php

namespace Awesomite\Nano\Traits;

use Awesomite\Nano\Container\Container;

/**
 * @internal
 */
trait ContainerTrait
{
    private $container;

    public function getContainer(): Container
    {
        return $this->container;
    }
}
