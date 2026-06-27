<?php

namespace App\Tests\Controller;

use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CharacterControllerTest extends WebTestCase
{
    private function createAuthenticatedClient(): \Symfony\Bundle\FrameworkBundle\KernelBrowser
    {
        return static::createClient([], [
            'HTTP_HOST' => 'local.guilde-des-seigneurs.com',
        ]);
    }

    public function testIndex(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/character');

        self::assertResponseIsSuccessful();
    }

    public function testNew(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/character/new');

        self::assertResponseIsSuccessful();
    }

    public function testCreate(): void
    {
        $client = $this->createAuthenticatedClient();
        $crawler = $client->request('GET', '/character/new');

        $form = $crawler->selectButton('Save')->form([
            'character[name]' => 'TestSeq22',
            'character[kind]' => 'Seigneur',
            'character[surname]' => 'Dupont',
            'character[caste]' => 'Noble',
            'character[knowledge]' => 'Epée',
            'character[intelligence]' => '100',
            'character[strength]' => '150',
            'character[life]' => '150',
        ]);

        $client->submit($form);
        self::assertResponseRedirects();

        self::assertSame(
            1,
            static::getContainer()->get(CharacterRepository::class)->count(['name' => 'TestSeq22'])
        );
    }

    public function testEdit(): void
    {
        $client = $this->createAuthenticatedClient();
        $character = static::getContainer()->get(CharacterRepository::class)->findOneBy([]);
        self::assertNotNull($character);

        $crawler = $client->request('GET', '/character/'.$character->getId().'/edit');

        $form = $crawler->selectButton('Update')->form([
            'character[name]' => 'ModifieSeq22',
        ]);

        $client->submit($form);
        self::assertResponseRedirects();

        $updated = static::getContainer()->get(CharacterRepository::class)->find($character->getId());
        self::assertSame('ModifieSeq22', $updated->getName());
        self::assertNotEmpty($updated->getSlug());
        self::assertNotNull($updated->getModification());
    }

    public function testLifeLevel(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/character/life/100');

        self::assertResponseIsSuccessful();
    }

    public function testDelete(): void
    {
        $client = $this->createAuthenticatedClient();
        $character = static::getContainer()->get(CharacterRepository::class)->findOneBy(['name' => 'TestSeq22']);
        self::assertNotNull($character);

        $characterId = $character->getId();
        $crawler = $client->request('GET', '/character/'.$characterId.'/edit');

        $client->submit($crawler->selectButton('Delete')->form());
        self::assertResponseRedirects('/character');

        self::assertNull(static::getContainer()->get(CharacterRepository::class)->find($characterId));
    }
}
