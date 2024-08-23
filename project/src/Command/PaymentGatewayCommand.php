<?php

namespace App\Command;

use App\Exception\UnknownPaymentMethodException;
use App\Services\Payment\DTO\Requests\PaymentRequestDto;
use App\Services\Payment\PaymentProcessorContext;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'process:payment',
    description: 'Process payment through Shift4|ACI',
)]
class PaymentGatewayCommand extends Command
{
    public function __construct(
        private PaymentProcessorContext $context,
    ){
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Process a payment via CLI')
            ->addArgument('gateway', InputArgument::REQUIRED, 'The payment gateway (shift4 or aci)')
            ->addArgument('amount', InputArgument::REQUIRED, 'The payment amount')
            ->addArgument('currency', InputArgument::REQUIRED, 'The payment currency')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {

            $paymentRequest = new PaymentRequestDto(
                $input->getArgument('amount'),
                $input->getArgument('currency'),
            );

            $response = $this->context->processPayment(
                $input->getArgument('gateway'),
                $paymentRequest
            );

            $output->writeln(sprintf('Process of payment gateway: %s', $input->getArgument('gateway')));
            $output->writeln(sprintf('Transaction ID: %s', $response->transactionId));
            $output->writeln(sprintf('Date of creating: %s', $response->created_at));
            $output->writeln(sprintf('Currency: %s', $response->currency));
            $output->writeln(sprintf('Amount: %d', $response->amount));
            $output->writeln(sprintf('Card Bin: %d', $response->cardBin));

        } catch (UnknownPaymentMethodException $exception){
            $output->writeln($exception->getMessage());
        }

        return Command::SUCCESS;
    }
}
