<?php

namespace App\Services\PaymentClients\DTO\Responses;

class ResultDetails
{
    public function __construct(
        public string $clearingInstituteName
    ) {}

    public function toArray(): array
    {
        return [
            'clearingInstituteName' => $this->clearingInstituteName
        ];
    }
}