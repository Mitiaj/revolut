<?php
declare(strict_types=1);

namespace Mitiaj\RevolutApi\Http;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpClient
 * @package Mitiaj\RevolutApi\Http
 */
class HttpClient implements HttpClientInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * HttpClient constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client) {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function request(string $uri, string $method = "GET", $options = []): ResponseInterface
    {
        $request = new Request($method, $uri, $this->processHeaders($options), $this->processBody($options));
        
        return $this->send($request);
    }

    /**
     * @inheritDoc
     */
    public function get(string $uri, $options = []): ResponseInterface
    {
        return $this->request($uri, 'GET', $options);
    }

    /**
     * @inheritDoc
     */
    public function post(string $uri, $options = []): ResponseInterface
    {
        return $this->request($uri, 'POST', $options);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $uri, $options = []): ResponseInterface
    {
        return $this->request($uri, 'DELETE', $options);
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    private function send(RequestInterface $request): ResponseInterface
    {
        $response = $this->client->sendRequest($request);

        $code = floor($response->getStatusCode() / 100);

        if ($code == 4) {
            throw new HttpResponseException($request, $response, "Http request failed.", $response->getStatusCode());
        }

        if ($code == 5) {
            throw new ServerException($request, $response, "Http request failed.", $response->getStatusCode());
        }

        return $response;
    }

    private function processBody($options): string
    {
        $body = $options['body'] ?? [];

        return http_build_query($body, '', "&");
    }

    private function processHeaders($options): array
    {
        $headers = [];
        foreach ($options['headers'] ?? [] as $key => $value) {
            $headers[strtolower($key)] = $value;
        }
        $body = $options['body'] ?? null;

        if (is_array($body) && count($body) > 0) {
            if (!isset($headers['content-type'])) {
                $headers['Content-Type'] = 'application/x-www-form-urlencoded';
            }
        }

        return $headers;
    }
}