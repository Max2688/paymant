<?php

namespace App\Service\Payment\Gateway;

use App\Service\Payment\Contract\PaymentGatewayContract;
use Eway\Rapid as Connect;
use Eway\Rapid\Model\Response\CreateTransactionResponse;
use Eway\Rapid\Enum\ApiMethod;
use Eway\Rapid\Enum\TransactionType;
use Illuminate\Http\Request;
use function config;

class Eway implements PaymentGatewayContract
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function getCard(): string
    {
        return $this->request->input('card');
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->request->input('name');
    }

    /**
     * @inheritDoc
     */
    public function getDate(): string
    {
        return $this->request->input('date');
    }

    /**
     * @inheritDoc
     */
    public function getMonth(): string
    {
        $month = explode('/',$this->getDate());

        return $month[0];
    }

    /**
     * @inheritDoc
     */
    public function getYear(): string
    {
        $year = explode('/',$this->getDate());

        return $year[1];
    }

    /**
     * @inheritDoc
     */
    public function getCvv(): string
    {
        return $this->request->input('cvv');
    }

    /**
     * @inheritDoc
     */
    public function getAmount(): float
    {
        return str_replace(',', '', $this->request->input('billing_total') ) * 100;
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): bool
    {
        $response = $this->getResponse();

        return $response->TransactionStatus;
    }

    /**
     * @return CreateTransactionResponse
     */
    private function getResponse(): CreateTransactionResponse
    {
        $gatewayClient = Connect::createClient(config('services.eway.key'),config('services.eway.password'));

        $response = $gatewayClient->createTransaction(ApiMethod::DIRECT, $this->getTransactionData());

        return $response;
    }

    /**
     * @return array
     */
    private function getTransactionData(): array
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
