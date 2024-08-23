<?php

namespace App\Services\Payment\DTO\Responses;

use App\Services\DataTransferUtilities;

class PaymentResponse extends DataTransferUtilities
{
    public function __construct(
        public readonly string $transactionId,
        public readonly string $created_at,
        public readonly string $currency,
        public readonly int $amount,
        public readonly int $cardBin,
    ){
    }
}