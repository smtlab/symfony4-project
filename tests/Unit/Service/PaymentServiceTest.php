<?php
declare(strict_types=1);
namespace App\Test\Service;

use App\Entity\Product;
use App\PaymentProvider\PayPal;
use App\PaymentProvider\RazorPay;
use App\Service\PaymentService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class PaymentServiceTest extends TestCase
{
    /**
     * test payment creation
     *
     * @dataProvider getData
     * @param string $paymentProvider
     * @return void
     */
    public function testCreate(string $paymentProvider): void
    {
        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())->method('persist');
        $emMock->expects($this->any())->method('flush');

        $paymentService = new PaymentService($this->getPaymentProviderMocks(), $emMock);

        $product = $this->createMock(Product::class);

        $payment = $paymentService->create($paymentProvider, $product);

        $this->assertEquals($payment->getProvider(), $paymentProvider);
    }

    private function getPaymentProviderMocks(): iterable
    {
        $paypalMock = $this->createMock(PayPal::class);
        $razorpayMock = $this->createMock(RazorPay::class);
        return [$paypalMock, $razorpayMock];
    }

    public function getData(): array
    {
        return [
            ['paypal'], ['razorpay']
        ];
    }
}
