<?php

namespace App\Service\Payment;

use App\Exceptions\UnknownPaymentMethodException;
use App\Service\Payment\Contract\PaymentContract;
use App\Service\Payment\Gateway\Eway;
use App\Service\Payment\Gateway\Stripe;
use Illuminate\Http\Request;

class PaymentService implements PaymentContract
{
    /**
     * @inheritDoc
     */
    public function getPaymentGateway(string $method, Request $request): PaymentGateway
    {
        switch ($method){
            case 'eway':
                $eway = new Eway($request);
                return new PaymentGateway($eway);
            case 'stripe':
                $stripe = new Stripe($request);
                return new PaymentGateway($stripe);
            default:
                throw new UnknownPaymentMethodException("Unknown Payment Method");

        }
    }
}
