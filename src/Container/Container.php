<?php

namespace Awesomite\Nano\Container;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private $data = [];

    private $lazy = [];

    /**
     * @var ContainerInterface[]
     */
    private $containers = [];

    public function get($id)
    {
        $id = (string)$id;

        if (isset($this->lazy[$id])) {
            $this->data[$id] = $this->callLazy($id);
            unset($this->lazy[$id]);
        }

        if (isset($this->data[$id])) {
            return $this->data[$id];
        }

        if ($subContainer = $this->hasInContainers($id)) {
            return $this->data[$id] = $subContainer->get($id);
        }

        throw new NotFoundException(sprintf('Not found "%s"', $id));
    }

    public function has($id)
    {
        $id = (string)$id;

        return isset($this->data[$id]) || isset($this->lazy[$id]) || (bool)$this->hasInContainers($id);
    }

    public function set(string $id, $value): self
    {
        $this->data[$id] = $value;

        return $this;
    }

    public function setLazy(string $id, callable $callable): self
    {
        $this->lazy[$id] = $callable;

        return $this;
    }

    public function includeContainer(ContainerInterface $container): self
    {
        $this->containers[] = $container;

        return $this;
    }

    private function callLazy(string $id)
    {
        return call_user_func($this->lazy[$id]);
    }

    /**
     * @param string $id
     *
     * @return bool|ContainerInterface
     */
    private function hasInContainers(string $id)
    {
        foreach ($this->containers as $container) {
            if ($container->has($id)) {
                return $container;
            }
        }

        return false;
    }
}
