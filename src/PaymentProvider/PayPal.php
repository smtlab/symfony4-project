<?php
declare(strict_types=1);
namespace App\PaymentProvider;

class PayPal implements PaymentProviderInterface
{
    public function pay(): void
    {
        // @TODO call paypal gateway
    }
}
