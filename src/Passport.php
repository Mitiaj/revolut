<?php


namespace Mitiaj\RevolutApi;

class Passport
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $tokenType;

    /**
     * @var string
     */
    private $refreshToken;

    /**
     * @var string
     */
    private $expiresAt;

    /**
     * Passport constructor.
     * @param string $accessToken
     * @param string $tokenType
     * @param string $refreshToken
     * @param string $expiresAt
     */
    public function __construct(string $accessToken, string $tokenType, string $refreshToken, \DateTime $expiresAt)
    {
        $this->accessToken = $accessToken;
        $this->tokenType = $tokenType;
        $this->refreshToken = $refreshToken;
        $this->expiresAt = $expiresAt;
    }

    /**
     * @return string
     */
    public function accessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function tokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * @return string
     */
    public function refreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @return string
     */
    public function expiresAt(): string
    {
        return $this->expiresAt;
    }
}
