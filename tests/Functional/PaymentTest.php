<?php
declare(strict_types=1);
namespace App\Test\Service;

use App\Entity\Product;
use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PaymentTest extends KernelTestCase
{
    /** @var \Doctrine\ORM\EntityManager */
    private $em;

    /** @var PaymentService */
    private $paymentService;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->paymentService = $kernel->getContainer()
            ->get(PaymentService::class);
        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * Test payment
     * @param string $paymentProvider
     * @param string $productName
     * @param float $productPrice
     * @return void
     * @dataProvider getPaymentProviders
     */
    public function testItCreatesPayment($paymentProvider, $productName, $productPrice): void
    {
        $product = new Product();
        $product->setName($productName);
        $product->setPrice($productPrice);
        $this->em->persist($product);
        $this->em->flush();

        $payment = $this->paymentService->create($paymentProvider, $product);
        $this->assertEquals($payment->getProvider(), $paymentProvider);
        $this->assertEquals($payment->getProduct()->getPrice(), $productPrice);
    }

    public function getPaymentProviders(): array
    {
        // payment provider, product name, product price
        return [
            ['paypal', 'Iphone', 1000],
            ['razorpay', 'Samsung S20', 800]
        ];
    }
}
