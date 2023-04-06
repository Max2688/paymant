<?php

namespace App\Service\Payment;

use App\Service\Payment\Gateway\Eway;
use App\Service\Payment\Gateway\Stripe;
use Illuminate\Http\Request;

class PaymentService
{
    private string $method;

    private Request $request;

    public function __construct(string $method, Request $request)
    {
        $this->method = $method;
        $this->request = $request;
    }

    public function getPaymentGateway()
    {
       switch ($this->method){
            case 'eway':
                return new Eway($this->request);
           case 'stripe':
               return new Stripe($this->request);
           default:
               throw new \Exception("Unknown Payment Method");

        }
    }
}
