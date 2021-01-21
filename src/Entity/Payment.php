<?php
declare(strict_types=1);
namespace App\Entity;

use App\Entity\Product;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="payments")
 * @ORM\Entity(repositoryClass="App\Repository\PaymentRepository")
 */
class Payment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="provider", type="string", length=20, nullable=false)
     */
    private $provider;

    /**
     * @var Product
     * One payment has one product
     * @ORM\OneToOne(targetEntity="App\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setProvider($provider): void
    {
        $this->provider = $provider;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }
}
