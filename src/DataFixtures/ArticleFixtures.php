<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       for($i=0;$i<10;$i++) {
        $article = new Article();
        $article->setTitle("Titre de l'article N°$i")
                ->setContent("Contenue de l'article N°$i")
                ->setImage("https://i.postimg.cc/gjL8Z64R/article.webp")
                ->setCreatedAt(new \DateTime());

        $manager->persist($article); 
       }

        $manager->flush();
    }
}
