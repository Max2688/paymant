<?php

namespace App\Service\Payment\Gateway;

use App\Exceptions\PaymentException;
use App\Service\Payment\Contract\PaymentGatewayContract;
use Illuminate\Http\Request;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;
use Stripe\StripeClient;
use Stripe\Charge;
use function config;

class Stripe implements PaymentGatewayContract
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
     * @return StripeClient
     */
    private function getStripeClient(): StripeClient
    {
        $stripe = new StripeClient(config('services.stripe.secret_key'));

        return $stripe;
    }
}
