<?php

namespace App\DTO;

use Illuminate\Support\Arr;

class PaymentGatewayDto
{
    public  string $card;
    public  string $date;
    public  float  $amount;
    public  string $name;
    public  string $cvv;

    public function __construct($paymentData)
    {
        $this->card = Arr::get($paymentData, 'card');
        $this->name = Arr::get($paymentData, 'name');
        $this->date = Arr::get($paymentData, 'date');
        $this->cvv = Arr::get($paymentData, 'cvv');
        $this->amount = Arr::get($paymentData, 'billing_total');
    }
}
