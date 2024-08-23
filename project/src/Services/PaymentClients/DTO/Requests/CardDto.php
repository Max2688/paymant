<?php

namespace App\Services\PaymentClients\DTO\Requests;

class CardDto
{
    public function __construct(
        public readonly string $holder,
        public readonly string $number,
        public readonly string $expMonth,
        public readonly int $expYear,
        public readonly int $cvv,
    ) {
    }

    /**
     * Convert the Card object to an associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'card.holder' => $this->holder,
            'card.number' => $this->number,
            'card.expiryMonth' => $this->expMonth,
            'card.expiryYear' => $this->expYear,
            'card.cvv' => $this->cvv,
        ];
    }
}