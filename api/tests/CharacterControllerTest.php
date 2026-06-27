<?php

namespace App\Tests;

use App\Entity\Character;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\Depends;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    /** @var array<string, mixed>|null */
    private ?array $content = null;

    private static ?int $userId = null;

    private static ?string $identifier = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('contact@example.com');
        $this->assertNotNull($testUser);
        self::$userId = $testUser->getId();
        $this->client->loginUser($testUser);
        $this->removeCharacterWithSlugGorthol();
    }

    /** Libère le slug « gorthol » imposé par CharacterService::update(), sans toucher au perso courant du scénario. */
    private function removeCharacterWithSlugGorthol(): void
    {
        /** @var EntityManagerInterface $em */
        $em = static::getContainer()->get('doctrine')->getManager();
        $conflict = $em->getRepository(Character::class)->findOneBy(['slug' => 'gorthol']);
        if ($conflict === null) {
            return;
        }
        if (self::$identifier !== null && $conflict->getIdentifier() === self::$identifier) {
            return;
        }
        $em->remove($conflict);
        $em->flush();
    }

    public function testCreate(): void
    {
        $this->assertNotNull(self::$userId);
        $userId = self::$userId;
        $this->client->request(
            'POST',
            '/characters/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            <<<'JSON'
{
    "kind": "Dame",
    "name": "Anardil",
    "surname": "Amie du soleil",
    "caste": "Magicien",
    "knowledge": "Sciences",
    "intelligence": 180,
    "strength": 180,
    "life": 150,
    "image": "/dames/anardil.webp",
    "user": "{$userId}"
}
JSON
        );
        $this->assertResponseCode(201);
        $this->assertJsonResponse();
        $this->defineIdentifier();
        $this->assertIdentifier();
    }

    public function testIndex(): void
    {
        // Tests with default values
        $this->client->request('GET', '/characters/');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();

        // Tests with page
        $this->client->request('GET', '/characters/?page=1');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();

        // Tests with page and size
        $this->client->request('GET', '/characters/?page=1&size=1');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();

        // Tests with size
        $this->client->request('GET', '/characters/?size=1');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
    }

    public function testLifeLevel(): void
    {
        $this->client->request('GET', '/characters/life/100');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
    }

    public function testImages(): void
    {
        // Tests without kind
        $this->client->request('GET', '/characters/images');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();

        $this->client->request('GET', '/characters/images/1');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();

        $this->client->request('GET', '/characters/images/3');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();

        // Tests with kind
        $this->client->request('GET', '/characters/images/dames');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();

        $this->client->request('GET', '/characters/images/dames/3');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();

        $this->client->request('GET', '/characters/images/seigneurs/3');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();

        $this->client->request('GET', '/characters/images/tourmenteurs/3');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();

        $this->client->request('GET', '/characters/images/tourmenteuses/3');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
    }

    #[Depends('testCreate')]
    public function testDisplay(): void
    {
        $this->assertNotNull(self::$identifier);
        $this->client->request('GET', '/characters/'.self::$identifier);
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
        $this->assertIdentifier();
    }

    public function testBadIdentifier(): void
    {
        $this->client->request('GET', '/characters/badIdentifier');
        $this->assertResponseCode(404);
    }

    public function testInexistingIdentifier(): void
    {
        $this->client->request('GET', '/characters/8f74f20597c5cf99dd42cd31331b7e6e2aeerror');
        $this->assertResponseCode(404);
    }

    #[Depends('testCreate')]
    public function testUpdate(): void
    {
        $this->assertNotNull(self::$identifier);
        $this->client->request(
            'PUT',
            '/characters/'.self::$identifier,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            <<<'JSON'
{
    "kind": "Seigneur",
    "name": "Gorthol"
}
JSON
        );
        $this->assertResponseCode(204);
    }

    #[Depends('testUpdate')]
    public function testDelete(): void
    {
        $this->assertNotNull(self::$identifier);
        $this->client->request('DELETE', '/characters/'.self::$identifier);
        $this->assertResponseCode(204);
    }

    protected function assertJsonResponse(): void
    {
        $response = $this->client->getResponse();
        $this->content = json_decode($response->getContent(), true, 50);
        $this->assertIsArray($this->content);
        $this->assertResponseIsSuccessful();
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            (string) $response->headers
        );
    }

    protected function assertResponseCode(int $code): void
    {
        $this->assertSame($code, $this->client->getResponse()->getStatusCode());
    }

    public function assertIdentifier(): void
    {
        $this->assertIsArray($this->content);
        $this->assertArrayHasKey('identifier', $this->content);
    }

    public function defineIdentifier(): void
    {
        $this->assertIsArray($this->content);
        self::$identifier = (string) $this->content['identifier'];
    }
}
