<?php
declare(strict_types=1);

namespace Mitiaj\RevolutApi\Api\Data;

use Carbon\Carbon;

class Account implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var float
     */
    protected $balance;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var bool
     */
    protected $public;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $updatedAt;

    public function __construct(array $data)
    {
        [
            'id' => $this->id,
            'name' => $this->name,
            'balance' => $this->balance,
            'currency' => $this->currency,
            'state' => $this->state,
            'public' => $this->public
        ] = $data;

        $this->createdAt = Carbon::parse($data['created_at']);
        $this->updatedAt = Carbon::parse($data['updated_at']);
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function currency(): string
    {
        return $this->currency;
    }

    /**
     * @return float
     */
    public function balance(): float
    {
        return $this->balance;
    }

    /**
     * @return string
     */
    public function state(): string
    {
        return $this->state;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * @return \DateTime
     */
    public function createdAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function updatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'balance' => $this->balance,
            'currency' => $this->currency,
            'state' => $this->state,
            'public' => $this->public,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
}