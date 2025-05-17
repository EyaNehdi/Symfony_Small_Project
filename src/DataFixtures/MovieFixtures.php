<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $movie = new Movie();
        $movie->setTitle('The Dark Knight');
        $movie->setReleaseYear(2008);
        $movie->setDescription('When the menace known as the Joker emerges from his mysterious past, he wreaks havoc and chaos on the people of Gotham. The Dark Knight must accept one of the greatest psychological and physical tests of his ability to fight injustice.');
        $movie->setImagePath('https://cdn.pixabay.com/photo/2024/06/22/16/55/ai-generated-8846672_1280.jpg');
        // Adding data to pivot table
        $movie->addActor($this->getReference('actor_1', Actor::class));
        $movie->addActor($this->getReference('actor_2', Actor::class));
        $manager->persist($movie);

        $movie2 = new Movie();
        $movie2->setTitle('Avengers: Endgame');
        $movie2->setReleaseYear(2019);
        $movie2->setDescription('After the devastating events of Avengers: Infinity War, the universe is in ruins. With the help of remaining allies, the Avengers assemble once more in order to reverse Thanos\' actions and restore balance to the universe.');
        $movie2->setImagePath('https://cdn.pixabay.com/photo/2023/04/17/15/31/ai-generated-7932706_1280.jpg');
        $movie2->addActor($this->getReference('actor_3', Actor::class));
        $movie2->addActor($this->getReference('actor_4', Actor::class));
        $manager->persist($movie2);

        $manager->flush();


    }
  public function getDependencies(): array
    {
        return [
            ActorFixtures::class,
        ];
    }
}
