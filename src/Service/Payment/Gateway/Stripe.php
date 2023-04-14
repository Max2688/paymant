<?php

namespace App\Service\Payment\Gateway;

use App\DTO\PaymentGatewayDto;
use App\Exceptions\PaymentException;
use App\Service\Payment\Contract\PaymentGatewayContract;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;
use Stripe\StripeClient;
use Stripe\Charge;
use function config;

class Stripe implements PaymentGatewayContract
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

        if($response->status === 'succeeded'){
            return true;
        }

        return false;
    }

    /**
     * @return Charge
     * @throws PaymentException
     * @throws InvalidRequestException
     */
    private function getResponse(): Charge
    {
        try {

            $response = $this->getStripeClient()->charges->create([
                'amount' => $this->getAmount(),
                'currency' => 'AUD',
                'source' =>  $this->generateValidPaymentToken(),
                'description' => '',
            ]);

        } catch (InvalidRequestException $e) {
            throw new PaymentException($e->getMessage());
        }

        return $response;
    }

    /**
     * @return int
     * @throws PaymentCardException
     * @throws CardException
     */
    private function generateValidPaymentToken()
    {
        try{

            $token = $this->getStripeClient()->tokens->create([
                'card' => [
                    'number' => $this->getCard(),
                    'exp_month' => $this->getMonth(),
                    'exp_year' => $this->getYear(),
                    'cvc' => $this->getCvv(),
                ],
            ]);

        } catch (CardException $e) {
            throw new PaymentException($e->getMessage());
        }

        return $token->id;
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

    /**
     * @return StripeClient
     */
    private function getStripeClient(): StripeClient
    {
        $stripe = new StripeClient(config('services.stripe.secret_key'));

        return $stripe;
    }
}
