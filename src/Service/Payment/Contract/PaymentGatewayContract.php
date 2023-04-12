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
     * @return string
     */
    public function getMonth(): string;

    /**
     * @return float
     */
    public function getAmount(): float;

    /**
     * @return string
     */
    public function getYear(): string;

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
