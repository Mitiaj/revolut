### Revolut api implementation

Api designed to access your own account and covers only Oauth2 authentication and listing accounts.

#### Install

```
composer require mitiaj/revolut-api
```

#### Setup additional dependencies
This library utilises psr-7 and psr-18 interfaces. Feel free to use any you want. This setup shows how to use guzzlehttp library.

```
composer require php-http/guzzle6-adapter
```

#### Usage

```
$httpClient = new \Mitiaj\RevolutApi\Http\HttpClient(GuzzleClient::createWithConfig([]));
$tokenFactory =  new \Mitiaj\RevolutApi\Api\JwtTokenFactory(
    'path/to/privatekey.pem',
    'domain.com'
);

$oauth = new \Mitiaj\RevolutApi\Oauth2\Client(
    'client-id',
    'https://callback-url-to-redirect-after-authentication.com',
    $httpClient,
    $tokenFactory
);
```

##### Redirect to revolut for authentication
```
$oauth->redirect();
```

##### Handle code on callback
```
$passport = $oauth->handleCallback($_GET['code']);
```

Save `$passport` for futher usage. Pass `$passport` making api call;

##### Create api client
```
$apiClient = new \Mitiaj\RevolutApi\Api\ApiClient($httpClient, $tokenFactory, 'rtspiHAxMbOiK1FOWFVFloeRO6frQtzLBUrytorfS94');
```

##### Call api
```
$accounts = $apiClient->accounts($passport);
```