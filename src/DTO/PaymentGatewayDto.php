<?php

namespace App\DTO;

use Illuminate\Support\Arr;

class PaymentGatewayDto
{
    private  string $card;
    private  string $date;
    private  float  $amount;
    private  string $name;
    private  string $cvv;

    public function __construct(array $paymentData)
    {
        $this->card = Arr::get($paymentData, 'card');
        $this->name = Arr::get($paymentData, 'name');
        $this->date = Arr::get($paymentData, 'date');
        $this->cvv = Arr::get($paymentData, 'cvv');
        $this->amount = Arr::get($paymentData, 'billing_total');
    }

    /**
     * @return string
     */
    public function getCard(): string
    {
        return $this->card;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCvv(): string
    {
        return $this->cvv;
    }


}
