<?php

namespace App\Services\PaymentClients;

use App\Services\PaymentClients\DTO\Requests\AciPaymentRequest;
use App\Services\PaymentClients\DTO\Responses\AciResponsePayment;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class AciClient
{
    /**
     * @param HttpClientInterface $aci
     * @param SerializerInterface $serializer
     */
    public function __construct(
        private HttpClientInterface $aci,
        private SerializerInterface $serializer
    ){
    }

    /**
     * Make request
     *
     * @param string $endpoint API Endpoint
     * @param string $responsePayloadClass Payload Class
     * @param array $parameters Request data
     * @param string $requestMethod Request Method to use default: POST
     */
    private function request(
        string $endpoint,
        string $responsePayloadClass,
        array  $parameters = [],
        string $requestMethod = 'POST'
    ){

        $response = match ($requestMethod) {
            'GET' => $this->aci->request($requestMethod, $endpoint, [
                'query' => $parameters,
            ]),
            default => $this->aci->request($requestMethod, $endpoint, [
                'body' =>  $this->buildCustomQueryString($parameters),
            ]),
        };

        return $this->serializer->deserialize(
            $response->getContent(),
            $responsePayloadClass,
            'json'
        );
    }

    public function processPayment(AciPaymentRequest $request): AciResponsePayment
    {

        return $this->request(
            'payments',
            AciResponsePayment::class,
            $request->toArray()
        );
    }

    private function buildCustomQueryString(array $data): string
    {
        $queryString = '';

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    $queryString .= $subKey . '=' . urlencode($subValue) . '&';
                }
            } else {
                $queryString .= $key . '=' . urlencode($value) . '&';
            }
        }

        return rtrim($queryString, '&');
    }
}