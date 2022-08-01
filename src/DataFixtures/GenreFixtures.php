<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GenreFixtures extends Fixture
{
    private $defaultGenre = [
        'classical', ' blues', ' ambient', 'easy', ' electronic', 'folk music', 'funk', 'heavy metal',
        'hip hop', 'jazz', 'latin music', 'grunge', 'opera', 'pop music', 'rap', 'reggae', 'rock', 'techno'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->defaultGenre as $itemGenre) {
            $genre = new Genre();
            $genre->setName($itemGenre);

            $manager->persist($genre);

            $manager->flush();
        }
    }
}