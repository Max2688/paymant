<?php

namespace App\Service\Payment;

use App\Service\Payment\Contract\PaymentContract;

class PaymentGateway
{
    private PaymentContract $paymentMethod;

    /**
     * @param PaymentContract $paymentMethod
     */
    public function __construct(PaymentContract $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }


    public function setPaymentMethod(PaymentContract $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getStatus()
    {
        return $this->paymentMethod->getResponse();
    }

}
