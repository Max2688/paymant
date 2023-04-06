<?php

namespace App\Service\Payment\Contract;

interface PaymentContract
{
    public function getCard();
    public function getDate();
    public function getMonth();
    public function getAmount();
    public function getYear();
    public function getName();
    public function getCvv();
    public function getStatus();
}
