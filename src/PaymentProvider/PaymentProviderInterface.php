<?php
namespace App\PaymentProvider;

interface PaymentProviderInterface
{
    /**
     * Api calls to payment gateway
     *
     * @return void
     */
    public function pay(): void;
}
