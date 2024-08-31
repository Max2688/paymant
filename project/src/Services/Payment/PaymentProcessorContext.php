<?php

namespace App\Services\Payment;

use App\Exception\UnknownPaymentMethodException;
use App\Services\Payment\DTO\Responses\PaymentResponse;
use App\Services\Payment\DTO\Requests\PaymentRequestDto;

final class PaymentProcessorContext
{
    public function __construct(
        private iterable $gateways
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
        $paymentType = PaymentGatewayType::tryFrom($gatewayType);

        if ($paymentType === null) {
            throw new UnknownPaymentMethodException('Invalid gateway type');
        }

        foreach ($this->gateways as $gateway){
            if($gateway->getType() === $paymentType){
                return $gateway->processPayment($requestDto);
            }
        }

        return null;

    }
}
