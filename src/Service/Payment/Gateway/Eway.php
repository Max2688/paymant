<?php

namespace App\Service\Payment\Gateway;

use App\DTO\PaymentGatewayDto;
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
     * @param PaymentGatewayDto $paymentDto
     */
    public function __construct(
        protected PaymentGatewayDto $paymentDto
    ){}


    /**
     * @inheritDoc
     */
    public function getCard(): string
    {
        return $this->paymentDto->card;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->paymentDto->name;
    }

    /**
     * @inheritDoc
     */
    public function getDate(): string
    {
        return $this->paymentDto->date;
    }

    /**
     * @inheritDoc
     */
    public function getCvv(): string
    {
        return $this->paymentDto->cvv;
    }

    /**
     * @inheritDoc
     */
    public function getAmount(): float
    {
        return str_replace(',', '', $this->paymentDto->amount ) * 100;
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

    /**
     * @return string
     */
    private function getMonth(): string
    {
        $month = explode('/',$this->getDate());

        return $month[0];
    }

    /**
     * @return string
     */
    private function getYear(): string
    {
        $year = explode('/',$this->getDate());

        return $year[1];
    }
}
