<?php
declare(strict_types=1);
namespace App\Service;

use App\Entity\Payment;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Traversable;

class PaymentService
{
    /** @var iterable $paymentProviders */
    private $paymentProviders;

    /** @var EntityManagerInterface $em */
    private $em;

    public function __construct(
        iterable $paymentProviders,
        EntityManagerInterface $em
    ) {
        $this->paymentProviders = $paymentProviders;

        if ($this->paymentProviders instanceof Traversable) {
            $this->paymentProviders = iterator_to_array($paymentProviders);
        }

        $this->em = $em;
    }

    /**
     * Create payment for given product
     *
     * @param string $provider
     * @param Product $product
     * @return Payment
     */
    public function create(string $provider, Product $product): Payment
    {
        if (isset($this->paymentProviders[$provider])) {
            $this->paymentProviders[$provider]->pay($product);
        }

        $payment = new Payment();
        $payment->setProduct($product);
        $payment->setProvider($provider);
        $this->em->persist($payment);
        $this->em->flush();

        return $payment;
    }
}
