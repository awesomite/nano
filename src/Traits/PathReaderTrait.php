<?php

namespace Awesomite\Nano\Traits;

use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
trait PathReaderTrait
{
    /**
     * @var callable|null
     */
    private $pathReaderCallback = null;

    /**
     * Function used to getting path from instance of Request class, e.g.
     *
     * function (Request $request): string
     * {
     *     return $request->getUri();
     * }
     *
     * @param callable $callback
     *
     * @return $this
     *
     * @see Request
     * @see http://api.symfony.com/3.3/Symfony/Component/HttpFoundation/Request.html
     */
    public function setPathReader(callable $callback)
    {
        $this->pathReaderCallback = $callback;

        return $this;
    }

    protected function readPath(Request $request): string
    {
        if (!is_null($this->pathReaderCallback)) {
            $path = call_user_func($this->pathReaderCallback, $request);
            if (!is_string($path)) {
                throw new \DomainException(sprintf(
                    'Callback defined in %s::setPathReader must return string, %s given',
                    static::class,
                    is_object($path) ? get_class($path) : gettype($path)
                ));
            }

            return $path;
        }

        return $this->baseReadPath($request);
    }

    private function baseReadPath(Request $request): string
    {
        return $request->getBaseUrl() . $request->getPathInfo();
    }
}
