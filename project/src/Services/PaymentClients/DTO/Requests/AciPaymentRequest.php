<?php

namespace App\Services\PaymentClients\DTO\Requests;

use App\Services\DataTransferUtilities;

class AciPaymentRequest extends DataTransferUtilities
{
    public function __construct(
        public readonly string $entityId,
        public readonly int $amount,
        public readonly string $currency,
        public readonly string $paymentBrand,
        public readonly string $paymentType,
        public readonly CardDto $card,
    ){
    }
}