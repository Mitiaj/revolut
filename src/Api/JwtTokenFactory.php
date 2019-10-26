<?php
declare(strict_types=1);

namespace Mitiaj\RevolutApi\Api;

use Ahc\Jwt\JWT;

class JwtTokenFactory implements JwtTokenFactoryInterface
{
    /**
     * @var string
     */
    private $privateKeyPath;

    /**
     * @var string
     */
    private $host;

    /**
     * JwtTokenFactory constructor.
     * @param string $privateKeyPath
     * @param string $host
     */
    public function __construct(string $privateKeyPath, string $host)
    {
        $this->privateKeyPath = $privateKeyPath;
        $this->host = $host;
    }
    
    public function create(string $clientId): string
    {
        return (new JWT($this->privateKeyPath, 'RS256'))
            ->encode([
            'iss' => $this->host,
            'sub' => $clientId,
            'aud' => 'https://revolut.com',
        ]);
    }
}