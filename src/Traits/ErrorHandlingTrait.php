<?php

namespace Awesomite\Nano\Traits;

use Awesomite\ErrorDumper\Cloners\ClonedException;
use Awesomite\ErrorDumper\Handlers\ErrorHandler;
use Awesomite\ErrorDumper\Listeners\ListenerClosure;
use Awesomite\ErrorDumper\Views\ViewHtml;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
trait ErrorHandlingTrait
{
    private $debugMode = false;

    private $errorHandler;

    private $errorCallback = null;

    public function enableDebugMode(): self
    {
        $this->debugMode = true;

        return $this;
    }

    public function enableErrorHandling(bool $exitOnError = true)
    {
        if (is_null($this->errorHandler)) {
            $this->errorHandler = (new ErrorHandler())
                ->pushListener(new ListenerClosure(function ($exception) {
                    $this->errorHandle($exception);
                }))
                ->exitAfterTrigger($exitOnError)
                ->register();
        }

        return $this;
    }

    public function onError(callable $callback): self
    {
        $this->errorCallback = $callback;

        return $this;
    }

    private function errorHandle(\Throwable $exception)
    {
        $done = false;

        if ($this->debugMode) {
            (new ViewHtml())->display(new ClonedException($exception));
            $done = true;
        }

        if (!is_null($this->errorCallback)) {
            call_user_func($this->errorCallback, $exception);
            $done = true;
        }

        if (!$done) {
            if (!headers_sent() && php_sapi_name() !== 'cli') {
                http_response_code(Response::HTTP_SERVICE_UNAVAILABLE);
                header('Content-Type: text/plain; charset=UTF-8');
            }

            echo 'Internal error';
        }
    }
}
