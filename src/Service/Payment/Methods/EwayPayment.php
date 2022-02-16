<?php

namespace App\Service\Payment\Methods;

use App\Service\Payment\PaymentFactory;
use  App\Service\Payment\Eway;
use App\Service\Payment\Contract\PaymentContract;
use Illuminate\Http\Request;

class EwayPayment extends PaymentFactory
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getPaymentMethod(): PaymentContract
    {
        return new Eway($this->request);
    }
}