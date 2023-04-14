<?php

namespace App\Providers;

use App\Service\Payment\Contract\PaymentGatewayFactoryContract;
use App\Service\Payment\PaymentGatewayFactory;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            PaymentGatewayFactoryContract::class,
            PaymentGatewayFactory::class
        );

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
