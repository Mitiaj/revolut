<?php
declare(strict_types=1);

namespace Mitiaj\RevolutApi\Api;

use Mitiaj\RevolutApi\Http\HttpResponseException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ApiException extends HttpResponseException
{
    public function __construct(RequestInterface $request, ResponseInterface $response, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($request, $response, $message, $code, $previous);
    }
}