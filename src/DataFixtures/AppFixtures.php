<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Photo;
use App\Entity\Tag;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = \Faker\Factory::create();


        $tags = [];

        for ($i = 0; $i < 10; $i++) {
            $tag = new Tag($faker->unique()->word);;
            $manager->persist($tag);
            $tags[] = $tag;
        }

        for ($i = 0; $i < 30; $i++) {
            $photo = new Photo($faker->sentence);
            $photo->setUrl($faker->imageUrl);
            $photo->setPrice($faker->randomFloat(2, 0, 100));
            $photo->setDescription($faker->paragraph);
            $photo->setMetaInfo([]);
            $photo->setCreatedAt(new \DateTimeImmutable("now"));


            for ($j = 0; $j < mt_rand(2, 8); $j++) {
                $photo->addTag($tags[array_rand($tags)]);
            }

            $manager->persist($photo);
        }
        $manager->flush();
    }
}
