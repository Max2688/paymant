<?php

namespace App\Service\Payment;

use App\DTO\PaymentGatewayDto;
use App\Exceptions\UnknownPaymentMethodException;
use App\Service\Payment\Contract\PaymentGatewayContract;
use App\Service\Payment\Contract\PaymentGatewayFactoryContract;
use App\Service\Payment\Gateway\Eway;
use App\Service\Payment\Gateway\Stripe;

class PaymentGatewayFactory implements PaymentGatewayFactoryContract
{
    /**
     * @inheritDoc
     */
    public function getPaymentGateway(string $method, PaymentGatewayDto $paymentData): PaymentGatewayContract
    {
        switch ($method){
            case 'eway':
                return new Eway($paymentData);
            case 'stripe':
                return new Stripe($paymentData);
            default:
                throw new UnknownPaymentMethodException("Unknown Payment Method");

        }
    }

}
