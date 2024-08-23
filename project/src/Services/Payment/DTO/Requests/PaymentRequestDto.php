<?php

namespace App\Services\Payment\DTO\Requests;

use App\Services\DataTransferUtilities;
use Symfony\Component\Validator\Constraints as Assert;

class PaymentRequestDto extends DataTransferUtilities
{
    public function __construct(

        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $amount,

        #[Assert\NotBlank]
        #[Assert\Length(['max' => 3])]
        #[Assert\Type('string')]
        public string $currency,
    ){
    }
}
