<?php
declare(strict_types=1);

namespace Mitiaj\RevolutApi\Api;

use Carbon\Carbon;
use Mitiaj\RevolutApi\Api\Data\Account;
use Mitiaj\RevolutApi\Http\HttpClientInterface;
use Mitiaj\RevolutApi\Http\HttpExceptionInterface;
use Mitiaj\RevolutApi\Http\HttpResponseException;
use Mitiaj\RevolutApi\Passport;
use Psr\Http\Message\ResponseInterface;

class ApiClient implements ApiClientInterface
{
    protected const BASE_URL = 'https://b2b.revolut.com';

    /**
     * @var HttpClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var JwtTokenFactoryInterface
     */
    protected $tokenFactory;

    /**
     * ApiClient constructor.
     * @param HttpClientInterface $client
     * @param JwtTokenFactoryInterface $tokenFactory
     * @param string $clientId
     */
    public function __construct(
        HttpClientInterface $client,
        JwtTokenFactoryInterface $tokenFactory,
        string $clientId
    ) {
        $this->client = $client;
        $this->clientId = $clientId;
        $this->tokenFactory = $tokenFactory;
    }

    /**
     * @param Passport $passport
     * @return Account[]
     * @throws HttpExceptionInterface
     */
    public function accounts(Passport $passport): array
    {
        return $this->callApi(function () use ($passport) {
            $response = $this->client->get(self::BASE_URL . '/api/1.0/accounts', [
                'headers' => [
                    'Authorization' => "Bearer {$passport->accessToken()}"
                ]
            ]);

            $accounts = [];
            foreach ($this->toJson($response) as $object) {
                $accounts[] = new Account($object);
            }

            return $accounts;
        });
    }

    /**
     * @param Passport $passport
     * @return Passport
     * @throws HttpExceptionInterface
     */
    public function refreshToken(Passport $passport): Passport
    {
        return $this->callApi(function () use ($passport) {
            $response = $this->client->post(self::BASE_URL . '/api/1.0/auth/token', [
                'body' => [
                    'grant_type' => 'refresh_token',
                    'client_id' => $this->clientId,
                    'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
                    'refresh_token' => $passport->refreshToken(),
                    'client_assertion' => $this->tokenFactory->create($this->clientId),
                ]
            ]);

            $body = $this->toJson($response);

            return new Passport(
                $body['access_token'],
                $body['token_type'],
                $passport->refreshToken(),
                Carbon::now()->addSeconds($body['expires_in'])
            );
        });
    }

    private function callApi(callable $call)
    {
        try {
            return $call();

        } catch (HttpExceptionInterface $ex) {
            throw $this->mapException($ex);
        }
    }

    private function mapException(HttpResponseException $exception): ApiException
    {
        if ($exception->getResponse()->getStatusCode() === 401) {
            return new AuthorizationException(
                $exception->getRequest(),
                $exception->getResponse(),
                'Unauthorized',
                401,
                $exception
            );
        }

        $body = $this->toJson($exception->getResponse());
        if (!isset($body['error']) && !isset($body['error_description'])) {
            return new ApiException(
                $exception->getRequest(),
                $exception->getResponse(),
                'Unknown API error',
                $exception->getCode(),
                $exception
            );
        }

        if ($body['error'] == 'unauthorized_client') {
            return new AuthenticationException(
                $exception->getRequest(),
                $exception->getResponse(),
                $body['error_description'],
                $exception->getCode(),
                $exception
            );
        }

        return new ApiException(
            $exception->getRequest(),
            $exception->getResponse(),
            $body['error'] . ': ' .$body['error_description'],
            $exception->getCode(),
            $exception
        );
    }

    private function toJson(ResponseInterface $response): array
    {
        return json_decode($response->getBody()->getContents(), true);
    }
}

