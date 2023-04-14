<?php

namespace App\Service\Payment\Contract;

use App\DTO\PaymentGatewayDto;
use App\Exceptions\UnknownPaymentMethodException;

interface PaymentGatewayFactoryContract
{
    /**
     * @return PaymentGatewayContract
     * @throws UnknownPaymentMethodException
     */
    public function getPaymentGateway(string $method, PaymentGatewayDto $paymentData): PaymentGatewayContract;
}