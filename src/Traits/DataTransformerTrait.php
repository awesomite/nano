<?php

namespace Awesomite\Nano\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @internal
 */
trait DataTransformerTrait
{
    private $defaultHttpStatusCode = Response::HTTP_OK;

    private function transformToResponse($data): Response
    {
        if (is_object($data)) {
            if ($data instanceof Response) {
                return $data;
            }

            if (method_exists($data, '__toString')) {
                return new StreamedResponse(function () use ($data) {
                    echo $data;
                }, $this->defaultHttpStatusCode);
            }
        }

        if (is_string($data)) {
            return new Response($data, $this->defaultHttpStatusCode);
        }

        if (is_scalar($data) || is_array($data) || is_null($data)) {
            $result = new JsonResponse($data, $this->defaultHttpStatusCode);
            $result->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRETTY_PRINT);

            return $result;
        }

        throw new \LogicException(sprintf(
            'Cannot convert %s to response',
            is_object($data) ? get_class($data) : gettype($data)
        ));
    }
}
