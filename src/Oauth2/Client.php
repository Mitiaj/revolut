<?php
declare(strict_types=1);

namespace Mitiaj\RevolutApi\Oauth2;

use Carbon\Carbon;
use Mitiaj\RevolutApi\Api\JwtTokenFactory;
use Mitiaj\RevolutApi\Http\HttpClient;
use Mitiaj\RevolutApi\Passport;

class Client
{
    /**
     * @var string
     */
    protected $authUrl = 'https://business.revolut.com';

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $callbackUrl;

    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var JwtTokenFactory
     */
    private $tokenFactory;


    public function __construct(
        string $clientId,
        string $callbackUrl,
        HttpClient $client,
        JwtTokenFactory $tokenFactory
    ) {
        $this->clientId = $clientId;
        $this->callbackUrl = $callbackUrl;
        $this->client = $client;
        $this->tokenFactory = $tokenFactory;
    }

    /**
     * @param string $scope
     * @param bool $return
     * @return string
     */
    public function redirect($scope = 'READ', $return = false)
    {
        $url = "{$this->authUrl}/app-confirm?client_id={$this->clientId}&response_type=code&redirect_uri={$this->callbackUrl}&scope={$scope}";

        if ($return) {
            return $url;
        }

        header("Location: {$url}");

        printf('<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="0;url=%1$s" />
        <title>Redirecting to %1$s</title>
    </head>
    <body>
        Redirecting to <a href="%1$s">%1$s</a>.
    </body>
</html>', htmlspecialchars($url, ENT_QUOTES, 'UTF-8'));

        die();
    }

    /**
     * @param string $code
     * @return Passport
     * @throws \Mitiaj\RevolutApi\Http\HttpExceptionInterface
     */
    public function handleCallback(string $code): Passport
    {
        $response = $this->client->post('https://b2b.revolut.com/api/1.0/auth/token', [
            'body' => [
                'grant_type' => 'authorization_code',
                'client_id' => $this->clientId,
                'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
                'code' => $code,
                'client_assertion' => $this->tokenFactory->create($this->clientId),
            ]
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        return new Passport(
            $body['access_token'],
            $body['token_type'],
            $body['refresh_token'],
            Carbon::now()->addSeconds($body['expires_in'])
        );
    }
}
