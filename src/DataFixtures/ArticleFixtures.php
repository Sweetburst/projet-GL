<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use app\Entity\Article;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 10; $i++){
            $article = new Article();
            $article->setTitle("Tire de l'article n°$i")
                    ->setContent("<p>Contenu de l'article n)$i</p>")
                    ->setImage("http://placehold.it/350x150")
                    ->setCreatedAt(new \DateTime());

                $manager->persist($article);
        }

        $manager->flush();
    }
}
