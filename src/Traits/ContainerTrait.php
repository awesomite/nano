<?php

namespace Awesomite\Nano\Traits;

use Psr\Container\ContainerInterface;

/**
 * @internal
 */
trait ContainerTrait
{
    private $container;

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
