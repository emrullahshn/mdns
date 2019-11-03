<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class MockDataFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $brand = (new Brand())
            ->setName('Moda');
        $manager->persist($brand);

        $brand2 = (new Brand())
            ->setName('Nisa');
        $manager->persist($brand2);

        $category = (new Category())
            ->setName('Apparel');
        $manager->persist($category);

        $category2 = (new Category())
            ->setName('Footwear');
        $manager->persist($category2);

        $product = (new Product())
            ->setName('Red Dress')
            ->setPrice(100)
            ->setBrand($brand)
            ->setCategory($category)
            ;
        $manager->persist($product);

        $product2 = (new Product())
            ->setName('Green Dress')
            ->setPrice(79.99)
            ->setBrand($brand)
            ->setCategory($category)
        ;
        $manager->persist($product2);

        $product3 = (new Product())
            ->setName('Blue Pants')
            ->setPrice(57.9)
            ->setBrand($brand)
            ->setCategory($category)
        ;
        $manager->persist($product3);

        $product4 = (new Product())
            ->setName('Black Boots')
            ->setPrice(250)
            ->setBrand($brand2)
            ->setCategory($category2)
        ;
        $manager->persist($product4);

        $product5 = (new Product())
            ->setName('White Sneakers')
            ->setPrice(450)
            ->setBrand($brand2)
            ->setCategory($category2)
        ;
        $manager->persist($product5);

        $product6 = (new Product())
            ->setName('Yellow Jumper')
            ->setPrice(89)
            ->setBrand($brand)
            ->setCategory($category)
        ;
        $manager->persist($product6);

        $product7 = (new Product())
            ->setName('Brown Boots')
            ->setPrice(250)
            ->setBrand($brand2)
            ->setCategory($category2)
        ;
        $manager->persist($product7);

        $manager->flush();
    }
}
