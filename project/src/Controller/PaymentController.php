<?php

namespace App\Controller;

use App\Services\Payment\DTO\Requests\PaymentRequestDto;
use App\Services\Payment\PaymentProcessorContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController
{
    public function __construct(
        private PaymentProcessorContext $context,
    ){
    }

    #[Route('api/process/payment/{gateway}', name: 'app_payment', methods: ['POST'], format: 'json')]
    public function processPayment(
        string $gateway,
        #[MapRequestPayload] PaymentRequestDto $paymentRequest
    ): JsonResponse
    {
        $response = $this->context->processPayment(
            $gateway,
            $paymentRequest
        );

        return new JsonResponse($response->toArray(), JsonResponse::HTTP_CREATED);
    }
}
