<?php

namespace App\Services\PaymentClients\DTO\Responses;

class Risk
{
    public function __construct(
        public string $score
    ) {}

    public function toArray(): array
    {
        return [
            'score' => $this->score
        ];
    }
}