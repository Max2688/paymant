<?php

namespace App\Services\Payment\Gateway;

use App\Services\Payment\Contract\PaymentGatewayInterface;
use App\Services\Payment\DTO\Requests\PaymentRequestDto;
use App\Services\Payment\DTO\Responses\PaymentResponse;
use App\Services\PaymentClients\AciClient;
use App\Services\PaymentClients\DTO\Requests\AciPaymentRequest;
use App\Services\PaymentClients\DTO\Requests\CardDto;

final class AciPaymentGateway implements PaymentGatewayInterface
{
    public function __construct(
        private AciClient $aciClient,
    ){
    }

    /**
     * @inheritDoc
     */
    public function processPayment(PaymentRequestDto $requestDto): ?PaymentResponse
    {
        $card = new CardDto(
            'Jane Jones',
            4200000000000000,
            '05',
            2034,
            123,
        );

        $request = new AciPaymentRequest(
            '8a8294174b7ecb28014b9699220015ca',
            $requestDto->amount,
            $requestDto->currency,
             'VISA',
            'DB',
            $card,
        );

        $response = $this->aciClient->processPayment($request);

        return new PaymentResponse(
            $response->id,
            $response->timestamp,
            $response->currency,
            $response->amount,
            $response->card->bin,
        );

    }
}
