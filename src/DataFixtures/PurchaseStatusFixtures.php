<?php

namespace App\DataFixtures;

use App\Entity\PurchaseStatus;
use App\Lists\PurchaseStatusReference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PurchaseStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            [
                'reference' => PurchaseStatusReference::WAITING_TO_BE_SENT,
                'label' => 'En attente d\envoie',
            ],
            [
                'reference' => PurchaseStatusReference::SENT,
                'label' => 'Envoyé',
            ]
        ];

        foreach ($data as $datum) {
            $product = new PurchaseStatus();
            $product
                ->setLabel($datum['label'])
                ->setReference($datum['reference'])
            ;

            $manager->persist($product);
        }

        $manager->flush();
        $manager->clear();
    }
}
