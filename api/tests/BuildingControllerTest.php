<?php

namespace App\Tests;

use App\Entity\Building;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\Depends;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BuildingControllerTest extends WebTestCase
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
        $this->removeBuildingWithSlug('chateau-stamlam');
        $this->removeBuildingWithSlug('chateau-oakenfield');
    }

    private function removeBuildingWithSlug(string $slug): void
    {
        /** @var EntityManagerInterface $em */
        $em = static::getContainer()->get('doctrine')->getManager();
        $conflict = $em->getRepository(Building::class)->findOneBy(['slug' => $slug]);
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
        $this->client->request(
            'POST',
            '/buildings/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            <<<'JSON'
{
    "name": "Chateau Stamlam",
    "caste": "Magicien",
    "image": "/buildings/chateau-stamlam.webp",
    "strength": 1800
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
        $this->client->request('GET', '/buildings/');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();

        // Tests with page
        $this->client->request('GET', '/buildings/?page=1');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();

        // Tests with page and size
        $this->client->request('GET', '/buildings/?page=1&size=1');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();

        // Tests with size
        $this->client->request('GET', '/buildings/?size=1');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
    }

    public function testImages(): void
    {
        $this->client->request('GET', '/buildings/images');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();

        $this->client->request('GET', '/buildings/images/1');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();

        $this->client->request('GET', '/buildings/images/3');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
    }

    #[Depends('testCreate')]
    public function testDisplay(): void
    {
        $this->assertNotNull(self::$identifier);
        $this->client->request('GET', '/buildings/'.self::$identifier);
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
        $this->assertIdentifier();
    }

    public function testBadIdentifier(): void
    {
        $this->client->request('GET', '/buildings/badIdentifier');
        $this->assertResponseCode(404);
    }

    public function testInexistingIdentifier(): void
    {
        $this->client->request('GET', '/buildings/8f74f20597c5cf99dd42cd31331b7e6e2aeerror');
        $this->assertResponseCode(404);
    }

    #[Depends('testCreate')]
    public function testUpdate(): void
    {
        $this->assertNotNull(self::$identifier);
        $this->client->request(
            'PUT',
            '/buildings/'.self::$identifier,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            <<<'JSON'
{
    "name": "Chateau Oakenfield",
    "caste": "Erudit"
}
JSON
        );
        $this->assertResponseCode(204);
    }

    #[Depends('testUpdate')]
    public function testDelete(): void
    {
        $this->assertNotNull(self::$identifier);
        $this->client->request('DELETE', '/buildings/'.self::$identifier);
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
