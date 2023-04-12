<?php

namespace App\Service\Payment\Contract;

use App\Exceptions\UnknownPaymentMethodException;
use App\Service\Payment\PaymentGateway;

interface PaymentContract
{
    /**
     * @return PaymentGateway
     * @throws UnknownPaymentMethodException
     */
    public function getPaymentGateway(string $method, Request $request): PaymentGateway;
}