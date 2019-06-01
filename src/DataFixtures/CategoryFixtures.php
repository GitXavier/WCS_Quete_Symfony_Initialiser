<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{

    const CATEGORIES = [
        'PHP',
        'Java',
        'Javascript',
        'Ruby',
        'DevOps'
    ];

    public function load(ObjectManager $manager)
    {
        /* Generation du contenu avec une Boucle

            for($i = 1; $i <= 50; $i++){
            $category = new category();
            $category->setName('Docker' . $i);
            $manager->persist($category);
        }*/

            foreach (self::CATEGORIES as $key => $categoryName) {
                $category = new category();
                $category->setName($categoryName);
                $manager->persist($category);
                $this->addReference('categorie_' . $key, $category);
            }

            $manager->flush();
    }
}