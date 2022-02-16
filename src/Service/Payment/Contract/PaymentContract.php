<?php

namespace App\Service\Payment\Contract;

interface PaymentContract
{
    public function getCard();
    public function getResponse();
    public function getStatus();
    public function getTransactionId();
    public function getResponseMessage();
    public function getErrors();
}