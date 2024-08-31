<?php

namespace App\Services\Payment\Contract;

use App\Services\Payment\DTO\Requests\PaymentRequestDto;
use App\Services\Payment\DTO\Responses\PaymentResponse;
use App\Services\Payment\PaymentGatewayType;

interface PaymentGatewayInterface
{
    /**
     * @param PaymentRequestDto $chargeDto
     * @return PaymentResponse
     */
    public function processPayment(PaymentRequestDto $chargeDto): ?PaymentResponse;

    /**
     * @return PaymentGatewayType
     */
    public function getType(): PaymentGatewayType;
}