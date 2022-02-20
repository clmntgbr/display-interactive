<?php

namespace App\Entity;

use App\Repository\PurchaseStatusHistoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=PurchaseStatusHistoryRepository::class)
 */
class PurchaseStatusHistory
{
    use TimestampableEntity;

    /**
     * @var int|null
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $id;

    /**
     * @var Purchase|null
     * @ORM\ManyToOne(targetEntity=Purchase::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    public $purchase;

    /**
     * @var PurchaseStatus|null
     * @ORM\ManyToOne(targetEntity=PurchaseStatus::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    public $purchaseStatus;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPurchase(): ?Purchase
    {
        return $this->purchase;
    }

    public function setPurchase(?Purchase $purchase): self
    {
        $this->purchase = $purchase;

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
