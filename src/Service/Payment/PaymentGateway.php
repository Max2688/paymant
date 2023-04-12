<?php

namespace App\Service\Payment;

use App\Service\Payment\Contract\PaymentGatewayContract;

class PaymentGateway
{
    /**
     * @var PaymentGatewayContract
     */
    private PaymentGatewayContract $paymentMethod;

    /**
     * @param PaymentGatewayContract $paymentMethod
     */
    public function __construct(PaymentGatewayContract $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return bool
     *
     * Get status for current payment
     */
    public function getStatus(): bool
    {
        return $this->paymentMethod->getStatus();
    }

}
