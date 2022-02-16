<?php

namespace App\Service\Payment;

use App\Service\Payment\Contract\PaymentContract;

abstract class PaymentFactory
{
    abstract public function getPaymentMethod(): PaymentContract;
}