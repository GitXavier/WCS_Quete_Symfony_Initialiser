<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }


    public function load(ObjectManager $manager)
    {

        // On configure dans quelles langues nous voulons nos données
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++){
            $article = new Article();

            //$article->setTitle('Framework PHP : Symfony 4');
            $article->setTitle(mb_strtolower($faker->sentence()));
            $article->setSlug($this->slugify->generate($article->getTitle()));
            //$article->setContent('Symfony 4, un framework sympa à connaitre !');
            $article->setContent(mb_strtolower($faker->text));



            $manager->persist($article);
            $article->setCategory($this->getReference('categorie_' . ($i+1)%5));
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
}