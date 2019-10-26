<?php

namespace Mitiaj\RevolutApi\Api;

use Mitiaj\RevolutApi\Api\Data\Account;
use Mitiaj\RevolutApi\Http\HttpExceptionInterface;
use Mitiaj\RevolutApi\Passport;

interface ApiClientInterface
{
    /**
     * @param Passport $passport
     * @return Account[]
     * @throws HttpExceptionInterface
     */
    public function accounts(Passport $passport): array;

    /**
     * @param Passport $passport
     * @return Passport
     * @throws HttpExceptionInterface
     */
    public function refreshToken(Passport $passport): Passport;
}