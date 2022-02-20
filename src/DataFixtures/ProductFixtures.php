<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [1221, 4324, 75672, 2123, 3213];

        foreach ($data as $datum) {
            $product = new Product();
            $product
                ->setId($datum)
            ;

            $manager->persist($product);
        }

        $manager->flush();
        $manager->clear();
    }
}
