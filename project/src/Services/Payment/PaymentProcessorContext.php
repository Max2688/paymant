<?php

namespace App\Services\Payment;

use App\Exception\UnknownPaymentMethodException;
use App\Services\Payment\DTO\Responses\PaymentResponse;
use App\Services\Payment\Gateway\AciPaymentGateway;
use App\Services\Payment\DTO\Requests\PaymentRequestDto;
use App\Services\Payment\Gateway\Shift4PaymentGateway;

class PaymentProcessorContext
{
    public function __construct(
        private Shift4PaymentGateway $shift4PaymentGateway,
        private AciPaymentGateway $aciPaymentGateway
    ){
    }

    /**
     * @param string $gatewayType
     * @param PaymentRequestDto $requestDto
     * @return PaymentResponse|null
     * @throws UnknownPaymentMethodException
     */
    public function processPayment(
        string $gatewayType,
        PaymentRequestDto $requestDto
    ): ?PaymentResponse
    {
        $gateway = match ($gatewayType) {
            'shift4' => $this->shift4PaymentGateway,
            'aci' => $this->aciPaymentGateway,
            default => throw new UnknownPaymentMethodException('Invalid gateway type'),
        };

        return $gateway->processPayment($requestDto);
    }
}
