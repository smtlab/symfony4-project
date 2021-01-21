<?php
declare(strict_types=1);
namespace App\PaymentProvider;

class RazorPay implements PaymentProviderInterface
{
    public function pay(): void
    {
        // @TODO call razorpay api
    }
}
