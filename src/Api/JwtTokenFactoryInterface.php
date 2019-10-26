<?php
declare(strict_types=1);

namespace Mitiaj\RevolutApi\Api;

interface JwtTokenFactoryInterface
{
    public function create(string $clientId): string;
}