<?php

namespace App\Service\Payment\Gateway;

use App\Service\Payment\Contract\PaymentContract;
use Eway\Rapid as Connect;
use Eway\Rapid\Client as Client;
use Eway\Rapid\Enum\ApiMethod;
use Eway\Rapid\Enum\TransactionType;
use Illuminate\Http\Request;
use function config;

class Eway implements PaymentContract
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getCard()
    {
        return $this->request->input('card');
    }

    public function getName()
    {
        return $this->request->input('name');
    }

    public function getDate()
    {
        return $this->request->input('date');
    }

    public function getMonth()
    {
        $month = explode('/',$this->getDate());

        return $month[0];
    }

    public function getYear()
    {
        $year = explode('/',$this->getDate());

        return $year[1];
    }

    public function getCvv()
    {
        return $this->request->input('cvv');
    }

    public function getAmount()
    {
        return str_replace(',', '', $this->request->input('billing_total') ) * 100;
    }

    public function getStatus()
    {
        $response = $this->getResponse();

        return $response->TransactionStatus;
    }

    private function getResponse()
    {
        $gatewayClient = Connect::createClient(config('services.eway.key'),config('services.eway.password'));

        $response = $gatewayClient->createTransaction(ApiMethod::DIRECT,$this->getTransaction());

        return $response;
    }

    private function getTransaction()
    {
        $this->getDate();

        $transaction = [
            'Customer' => [
                'CardDetails' => [
                    'Name' => $this->getName(),
                    'Number' =>$this->getCard(),
                    'ExpiryMonth' => $this->getMonth(),
                    'ExpiryYear' => $this->getYear(),
                    'CVN' => $this->getCvv(),
                ]
            ],
            'Payment' => [
                'TotalAmount' => $this->getAmount(),
            ],
            'TransactionType' => TransactionType::MOTO,
        ];

        return $transaction;
    }
}
