<?php

namespace App\Services\PaymentClients\DTO\Responses;

class CardResponse
{
    public function __construct(
        public string $bin,
        public string $last4Digits,
        public string $holder,
        public string $expiryMonth,
        public string $expiryYear
    ) {}

    public function toArray(): array
    {
        return [
            'bin' => $this->bin,
            'last4Digits' => $this->last4Digits,
            'holder' => $this->holder,
            'expiryMonth' => $this->expiryMonth,
            'expiryYear' => $this->expiryYear
        ];
    }
}