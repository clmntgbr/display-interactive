<?php

namespace App\Helper;

use App\Entity\Purchase;
use App\Entity\PurchaseStatus;
use App\Entity\PurchaseStatusHistory;
use Doctrine\ORM\EntityManagerInterface;

class PurchaseStatusHelper
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setStatus(string $reference, Purchase $purchase)
    {
        $purchaseStatus = $this->em->getRepository(PurchaseStatus::class)->findOneBy(['reference' => $reference]);

        if (null === $purchaseStatus) {
            throw new \Exception(sprintf('Purchase Status don\'t exist (reference : %s', $reference));
        }

        $purchase->setPurchaseStatus($purchaseStatus);

        $purchaseStatusHistory = new PurchaseStatusHistory();
        $purchaseStatusHistory
            ->setPurchaseStatus($purchaseStatus)
            ->setPurchase($purchase);

        $this->em->persist($purchase);
        $this->em->persist($purchaseStatusHistory);

        $this->em->flush();
    }
}