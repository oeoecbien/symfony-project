<?php

namespace App\DataFixtures;

use App\Entity\Character;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private SluggerInterface $slugger,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $charactersPath = dirname(__DIR__, 3).'/api/data/characters.json';
        $characters = json_decode((string) file_get_contents($charactersPath), true);

        foreach ($characters as $characterData) {
            $character = $this->setCharacter($characterData);
            $manager->persist($character);
        }

        $manager->flush();
    }

    public function setCharacter(array $characterData): Character
    {
        $character = new Character();
        foreach ($characterData as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($character, $method)) {
                $character->$method($value ?? null);
            }
        }
        $character->setSlug($this->slugger->slug($characterData['name'])->lower());
        $character->setIdentifier(hash('sha1', uniqid()));
        $character->setCreation(new \DateTime());
        $character->setModification(new \DateTime());

        return $character;
    }
}
