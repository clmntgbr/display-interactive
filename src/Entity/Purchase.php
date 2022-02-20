<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PurchaseRepository::class)
 */
class Purchase
{
    use TimestampableEntity;

    /**
     * @var string|null
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", options={"unsigned"=true})
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type("string")
     */
    private $id;

    /**
     * @var int|null
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type("integer")
     */
    private $quantity;

    /**
     * @var int|null
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type("integer")
     */
    private $price;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type("DateTime")
     */
    private $date;

    /**
     * @var PurchaseStatus|null
     * @ORM\ManyToOne(targetEntity=PurchaseStatus::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type(PurchaseStatus::class)
     */
    public $purchaseStatus;

    /**
     * @var Currency|null
     * @ORM\ManyToOne(targetEntity=Currency::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type(Currency::class)
     */
    public $currency;

    /**
     * @var Product|null
     * @ORM\ManyToOne(targetEntity=Product::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type(Product::class)
     */
    public $product;

    /**
     * @var Customer|null
     * @ORM\ManyToOne(targetEntity=Customer::class, cascade={"persist"}, inversedBy="purchases")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type(Customer::class)
     */
    public $customer;

    public function getId(): ?string
    {
        return $this->id;
    }

    /** @var string|null */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getPurchaseStatus(): ?PurchaseStatus
    {
        return $this->purchaseStatus;
    }

    public function setPurchaseStatus(?PurchaseStatus $purchaseStatus): self
    {
        $this->purchaseStatus = $purchaseStatus;

        return $this;
    }
}
