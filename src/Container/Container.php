<?php

namespace Awesomite\Nano\Container;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private $data = [];

    private $lazy = [];

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

        throw new NotFoundException(sprintf('Not found "%"', $id));
    }

    public function has($id)
    {
        $id = (string)$id;

        return isset($this->data[$id]) || isset($this->lazy[$id]);
    }

    public function set(string $id, $value)
    {
        $this->data[$id] = $value;

        return $this;
    }

    public function setLazy(string $id, callable $callable)
    {
        $this->lazy[$id] = $callable;

        return $this;
    }

    private function callLazy(string $id)
    {
        return call_user_func($this->lazy[$id]);
    }
}
