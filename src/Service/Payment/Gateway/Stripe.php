<?php

namespace App\Service\Payment\Gateway;

use App\Exceptions\PaymentException;
use App\Service\Payment\Contract\PaymentContract;
use Illuminate\Http\Request;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;
use function config;

class Stripe implements PaymentContract
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    private function getStripeClient()
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret_key'));

        return $stripe;
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

        if($response->status === 'succeeded'){
            return true;
        }

        return false;
    }

    private function getResponse()
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
}
