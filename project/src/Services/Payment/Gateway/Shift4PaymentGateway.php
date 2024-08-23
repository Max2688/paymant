<?php

namespace App\Services\Payment\Gateway;

use App\Services\Payment\Contract\PaymentGatewayInterface;
use App\Services\Payment\DTO\Requests\PaymentRequestDto;
use App\Services\Payment\DTO\Responses\PaymentResponse;
use Psr\Log\LoggerInterface;
use Shift4\Exception\Shift4Exception;
use Shift4\Request\CardRequest;
use Shift4\Request\ChargeRequest;
use Shift4\Shift4Gateway;

final class Shift4PaymentGateway implements PaymentGatewayInterface
{
    public function __construct(
        private string $secretKey,
        private LoggerInterface $logger
    ){
    }

    /**
     * @inheritDoc
     */
    public function processPayment(PaymentRequestDto $chargeDto): ?PaymentResponse
    {
        $gateway = new Shift4Gateway($this->secretKey);

        $card = new CardRequest();
        $card->number('4242424242424242');
        $card->expMonth(11);
        $card->expYear(2025);

        $request = new ChargeRequest();
        $request->amount($chargeDto->amount);
        $request->currency($chargeDto->currency);
        $request->card($card);

        try {
            $response = $gateway->createCharge($request);

            return new PaymentResponse(
                $response->getId(),
                $response->getCreated(),
                $response->getCurrency(),
                $response->getAmount(),
                $response->getCard()->getFirst6()
            );

        } catch (Shift4Exception $e) {
            $this->logger->error('Payment processing failed: ' . $e->getMessage());
        }

        return null;
    }

}
