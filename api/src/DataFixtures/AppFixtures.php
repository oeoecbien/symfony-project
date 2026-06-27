<?php

namespace App\DataFixtures;

use App\Entity\Building;
use App\Entity\Character;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private const JSON_CHARACTERS_PATH = __DIR__.'/../../data/characters.json';

    private const JSON_BUILDINGS_PATH = __DIR__.'/../../data/buildings.json';

    /** @var list<string> */
    private const SKIP_KEYS_FOR_SETTER_LOOP = ['slug', 'identifier', 'creation', 'modification', 'id', 'castle'];

    /** @var list<string> */
    private const SKIP_KEYS_BUILDING = ['slug', 'identifier', 'creation', 'id'];

    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly UserPasswordHasherInterface $hasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $users = $this->createUsers($manager);
        $randomBuildings = $this->createRandomBuildings($manager);
        $this->createRandomCharacters($manager, $randomBuildings);
        $jsonBuildings = $this->createJsonBuildings($manager);
        $this->createJsonCharacters($manager, $jsonBuildings, $users);
        $this->linkCelebornToChateauSilken($manager);
    }

    /**
     * @return list<User>
     */
    public function createUsers(ObjectManager $manager): array
    {
        $emails = [
            'contact@example.com',
            'info@example.com',
            'email@example.com',
        ];
        $users = [];
        foreach ($emails as $email) {
            $user = new User();
            $user->setEmail($email);
            $user->setPassword($this->hasher->hashPassword($user, 'StrongPassword*'));
            $now = new DateTimeImmutable();
            $user->setCreation($now);
            $user->setModification($now);
            if ($email === 'contact@example.com') {
                $user->setRoles(['ROLE_ADMIN']);
            }
            $manager->persist($user);
            $users[] = $user;
        }
        $manager->flush();

        return $users;
    }

    /**
     * @return list<Building>
     */
    public function createRandomBuildings(ObjectManager $manager): array
    {
        $buildings = [];
        $totalBuildings = 5;
        for ($i = 0; $i < $totalBuildings; ++$i) {
            $building = new Building();
            $name = 'Chateau '.$i;
            $building->setName($name);
            $building->setSlug((string) $this->slugger->slug($name)->lower());
            $building->setCaste('Guerrier '.$i);
            $building->setStrength(random_int(0, 2000));
            $building->setImage('/buildings/'.(string) $building->getSlug().'.webp');
            $building->setIdentifier(hash('sha1', uniqid()));
            $manager->persist($building);
            $buildings[] = $building;
        }
        $manager->flush();

        return $buildings;
    }

    /**
     * @param list<Building> $randomBuildings
     */
    public function createRandomCharacters(ObjectManager $manager, array $randomBuildings): void
    {
        $totalCharacters = 20;
        for ($i = 0; $i < $totalCharacters; ++$i) {
            $character = new Character();
            $character->setKind(0 === random_int(0, 1) ? 'Dame' : 'Seigneur');
            $character->setName('Anardil'.$i);
            $character->setSlug('anardil'.$i);
            $character->setSurname('Amie du soleil');
            $character->setCaste('Magicien');
            $character->setKnowledge('Sciences');
            $character->setIntelligence(random_int(100, 200));
            $character->setStrength(random_int(100, 200));
            $character->setLife(random_int(50, 200));
            $character->setIdentifier(hash('sha1', uniqid('', true)));
            $kind = strtolower((string) $character->getKind());
            $character->setImage('/'.$kind.'s/'.$kind.'.webp');
            $now = new DateTimeImmutable();
            $character->setCreation($now);
            $character->setModification($now);
            $character->setBuilding($randomBuildings[array_rand($randomBuildings)]);

            $manager->persist($character);
        }
        $manager->flush();
    }

    /**
     * @param list<Building> $jsonBuildings
     */
    public function createJsonCharacters(ObjectManager $manager, array $jsonBuildings, array $users): void
    {
        $raw = @file_get_contents(self::JSON_CHARACTERS_PATH);
        if ($raw === false) {
            return;
        }

        /** @var list<array<string, mixed>>|null $characters */
        $characters = json_decode($raw, true);
        if (!is_array($characters)) {
            return;
        }

        $charactersArray = [];
        foreach ($characters as $characterData) {
            if (!is_array($characterData)) {
                continue;
            }
            $character = $this->setCharacter($characterData);
            if ($users !== []) {
                $character->setUser($users[array_rand($users)]);
            }
            $manager->persist($character);
            $charactersArray[] = $character;
        }

        foreach ($jsonBuildings as $building) {
            foreach ($charactersArray as $character) {
                if ($building->getCaste() === $character->getCaste()) {
                    $building->addCharacter($character);
                }
            }
            $manager->persist($building);
        }
        $manager->flush();
    }

    /**
     * @param array<string, mixed> $characterData
     */
    public function setCharacter(array $characterData): Character
    {
        $character = new Character();
        foreach ($characterData as $key => $value) {
            if (in_array($key, self::SKIP_KEYS_FOR_SETTER_LOOP, true)) {
                continue;
            }
            if ($value === null && in_array($key, ['name', 'kind', 'surname'], true)) {
                continue;
            }
            $method = 'set'.ucfirst((string) $key);
            if (method_exists($character, $method)) {
                $character->{$method}($value ?? null);
            }
        }

        if ($character->getName() === null || $character->getName() === '') {
            $character->setName('Personnage');
        }
        if ($character->getKind() === null || $character->getKind() === '') {
            $character->setKind('Seigneur');
        }
        if ($character->getSurname() === null || $character->getSurname() === '') {
            $character->setSurname('—');
        }

        $nameForSlug = isset($characterData['name']) && is_scalar($characterData['name'])
            ? (string) $characterData['name']
            : (string) $character->getName();
        $character->setSlug((string) $this->slugger->slug($nameForSlug)->lower());
        $character->setIdentifier(hash('sha1', uniqid('', true)));
        $now = new DateTimeImmutable();
        $character->setCreation($now);
        $character->setModification($now);

        return $character;
    }

    /**
     * @return list<Building>
     */
    public function createJsonBuildings(ObjectManager $manager): array
    {
        $raw = @file_get_contents(self::JSON_BUILDINGS_PATH);
        if ($raw === false) {
            return [];
        }

        /** @var list<array<string, mixed>>|null $rows */
        $rows = json_decode($raw, true);
        if (!is_array($rows)) {
            return [];
        }

        $buildings = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $slug = $this->buildingSlugFromRow($row);
            $existingBuilding = $manager->getRepository(Building::class)->findOneBy(['slug' => $slug]);
            if ($existingBuilding instanceof Building) {
                $buildings[] = $existingBuilding;
                continue;
            }
            $buildings[] = $this->persistBuildingFromRow($manager, $row, $slug);
        }
        $manager->flush();

        return $buildings;
    }

    /**
     * @param array<string, mixed> $row
     */
    private function buildingSlugFromRow(array $row): string
    {
        $image = isset($row['image']) && is_string($row['image']) ? $row['image'] : null;
        if ($image !== null && preg_match('#/buildings/(.+)\.webp$#', $image, $matches)) {
            return $matches[1];
        }

        $name = isset($row['name']) && is_scalar($row['name']) ? (string) $row['name'] : 'Batiment';

        return (string) $this->slugger->slug($name)->lower();
    }

    /**
     * @param array<string, mixed> $row
     */
    private function persistBuildingFromRow(ObjectManager $manager, array $row, string $slug): Building
    {
        $building = new Building();
        foreach ($row as $key => $value) {
            if (in_array($key, self::SKIP_KEYS_BUILDING, true)) {
                continue;
            }
            if ($value === null && in_array($key, ['name', 'caste', 'strength'], true)) {
                continue;
            }
            $method = 'set'.ucfirst((string) $key);
            if (method_exists($building, $method)) {
                $building->{$method}($value ?? null);
            }
        }

        if ($building->getName() === null || $building->getName() === '') {
            $building->setName('Bâtiment');
        }
        if ($building->getCaste() === null || $building->getCaste() === '') {
            $building->setCaste('—');
        }
        if ($building->getStrength() === null) {
            $building->setStrength(0);
        }

        $building->setSlug($slug);
        $building->setIdentifier(hash('sha1', uniqid('', true)));
        $manager->persist($building);

        return $building;
    }

    private function linkCelebornToChateauSilken(ObjectManager $manager): void
    {
        $character = $manager->getRepository(Character::class)->findOneBy(['slug' => 'celeborn']);
        if ($character === null) {
            $character = $manager->getRepository(Character::class)->findOneBy(['name' => 'Celeborn']);
        }
        $building = $manager->getRepository(Building::class)->findOneBy(['slug' => 'chateau-silken']);
        if ($character instanceof Character && $building instanceof Building) {
            $character->setCastle($building);
            $manager->flush();
        }
    }
}
