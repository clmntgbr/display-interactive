<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Purchase;
use App\Entity\PurchaseStatus;
use App\Helper\PurchaseStatusHelper;
use App\Lists\PurchaseStatusReference;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;

class SendPurchaseService
{
    const PUT_URL = "https://display-interactive.free.beeceptor.com";

    /** @var EntityManagerInterface */
    private $em;

    /** @var Client */
    private $client;

    /** @var PurchaseStatusHelper */
    private $purchaseStatusHelper;

    public function __construct(EntityManagerInterface $em, PurchaseStatusHelper $purchaseStatusHelper)
    {
        $this->em = $em;
        $this->client = new Client();
        $this->purchaseStatusHelper = $purchaseStatusHelper;
    }

    public function send()
    {
        $datum = $this->createJson();

        $options = ['headers' => ['Accept' => 'application/json', 'Content-Type' => 'application/json'], 'body' => json_encode($datum)];

        $this->client->put(self::PUT_URL, $options);
    }

    private function createJson()
    {
        /** @var PurchaseStatus $purchaseStatus */
        $purchaseStatus = $this->em->getRepository(PurchaseStatus::class)->findOneBy(['reference' => PurchaseStatusReference::WAITING_TO_BE_SENT]);

        /** @var Customer[]|null $customers */
        $customers = $this->em->getRepository(Customer::class)->findCustomerWithPurchaseCommand($purchaseStatus);

        $datum = [];
        foreach ($customers as $customer) {

            /** @var Purchase[]|null $purchases */
            $purchases = $this->em->getRepository(Purchase::class)->findBy([
                'purchaseStatus' => $purchaseStatus,
                'customer' => $customer,
            ]);

            $purchasesData = [];
            foreach ($purchases as $purchase) {
                $purchasesData[] = $this->getPurchaseTemplate($purchase);
                $this->purchaseStatusHelper->setStatus(PurchaseStatusReference::SENT, $purchase);
            }

            $datum[] = $this->getCustomerTemplate($customer, $purchasesData);
        }

        return $datum;
    }

    private function getPurchaseTemplate(Purchase $purchase)
    {
        return [
            "product_id" => $purchase->getProduct()->getId(),
            "price" => $purchase->getPrice(),
            "currency" => $purchase->getCurrency()->getReference(),
            "quantity" => $purchase->getQuantity(),
            "purchased_at" => $purchase->getDate()->format('Y-m-d'),
        ];
    }

    private function getCustomerTemplate(Customer $customer, array $purchases)
    {
        return [
            "salutation" => $customer->getTitle(),
            "last_name" => $customer->getLastname(),
            "first_name" => $customer->getFirstname(),
            "email" => $customer->getEmail(),
            "purchases" => $purchases
        ];
    }
}