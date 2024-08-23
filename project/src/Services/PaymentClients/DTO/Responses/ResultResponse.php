<?php

namespace App\Services\PaymentClients\DTO\Responses;

class ResultResponse
{
    public function __construct(
        public string $code,
        public string $description
    ) {}

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'description' => $this->description
        ];
    }
}