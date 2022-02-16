<?php

namespace App\Service\Payment;

use App\Service\Payment\Contract\PaymentContract;
use Illuminate\Http\Request;
use Eway\Rapid\Client as Client;
use Eway\Rapid as Connect;
use Eway\Rapid\Enum\ApiMethod;
use Eway\Rapid\Enum\TransactionType;

class Eway implements PaymentContract
{
    private $apiKey = '';

    private $apiPassword = '';

    private $apiEndpoint = Client::MODE_SANDBOX;

    private $request;

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

    public function getTransaction()
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

    public function getResponse()
    {
        $gatewayConnect = Connect::createClient($this->apiKey,$this->apiPassword,$this->apiEndpoint);
        $response = $gatewayConnect->createTransaction(ApiMethod::DIRECT,$this->getTransaction());
        return $response;
    }

    public function getStatus()
    {
        return $this->getResponse()->TransactionStatus;
    }

    public function getTransactionId()
    {
        return $this->getResponse()->TransactionID;
    }

    public function getResponseMessage()
    {
        return $this->getResponse()->ResponseMessage;
    }

    public function getErrors()
    {
        return Connect::getMessage($this->getResponse()->Errors);
    }

}