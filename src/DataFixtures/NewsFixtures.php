<?php

namespace App\DataFixtures;

use App\Entity\News;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class NewsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i=0; $i < 150; $i++) { 
            $news = new News();
            $news->setAuthor($faker->name);
            $news->setTitle(ucfirst($faker->words(5, true)));
            $news->setContent($faker->paragraphs(5, true));
            $news->setRecordingDate(new DateTime());
            $manager->persist($news);
        }

        $manager->flush();
    }
}
