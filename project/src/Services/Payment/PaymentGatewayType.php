<?php

namespace App\Services\Payment;

enum PaymentGatewayType: string
{
    case PAYMENT_GATEWAY_SHIFT4 = 'shift4';
    case PAYMENT_GATEWAY_ACI = 'aci';
}