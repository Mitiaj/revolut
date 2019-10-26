<?php
declare(strict_types=1);

namespace Mitiaj\RevolutApi\Http;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface HttpClientInterface
 * @package Revolut\Api
 */
interface HttpClientInterface
{
    /**
     * @param string $uri
     * @param string $method
     * @param array $options
     * @return ResponseInterface
     * @throws HttpExceptionInterface
     */
    public function request(string $uri, string $method = "GET", $options = []): ResponseInterface;

    /**
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     * @throws HttpExceptionInterface
     */
    public function get(string $uri, $options = []): ResponseInterface;

    /**
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     * @throws HttpExceptionInterface
     */
    public function post(string $uri, $options = []): ResponseInterface;

    /**
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     * @throws HttpExceptionInterface
     */
    public function delete(string $uri, $options = []): ResponseInterface;
}
