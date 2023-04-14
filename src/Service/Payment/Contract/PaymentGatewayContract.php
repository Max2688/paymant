<?php

namespace App\Service\Payment\Contract;

interface PaymentGatewayContract
{
    /**
     * @return string
     */
    public function getCard(): string;

    /**
     * @return string
     */
    public function getDate(): string;


    /**
     * @return float
     */
    public function getAmount(): float;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getCvv(): string;

    /**
     * @return bool
     */
    public function getStatus(): bool;
}
