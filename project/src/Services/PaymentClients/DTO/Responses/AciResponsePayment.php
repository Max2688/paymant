<?php

namespace App\Services\PaymentClients\DTO\Responses;

use App\Services\DataTransferUtilities;

class AciResponsePayment extends DataTransferUtilities
{
    public function __construct(
        public readonly string $id,
        public readonly string $paymentType,
        public readonly string $paymentBrand,
        public readonly string $amount,
        public readonly string $currency,
        public readonly string $descriptor,
        public readonly ResultResponse $result,
        public readonly ResultDetails $resultDetails,
        public readonly CardResponse $card,
        public readonly Risk $risk,
        public readonly string $buildNumber,
        public readonly string $timestamp,
        public readonly string $ndc,
        public readonly string $source,
        public readonly string $paymentMethod,
        public readonly string $shortId
    ){
    }
}