<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    /** @var array<string, mixed>|null */
    private ?array $content = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->ensureSigninUserExists();
    }

    public function testSignin(): void
    {
        $this->client->request(
            'POST',
            '/signin',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            <<<'JSON'
{
    "username": "contact@example.com",
    "password": "StrongPassword*"
}
JSON
        );
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
        $this->assertIsArray($this->content);
        $this->assertArrayHasKey('token', $this->content);
    }

    public function testSignup(): void
    {
        $email = 'signup-test-'.uniqid('', true).'@example.com';
        $this->client->request(
            'POST',
            '/signup',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => $email,
                'password' => 'StrongPassword*',
            ], JSON_THROW_ON_ERROR)
        );
        $this->assertResponseCode(201);
        $this->assertJsonResponse();
        $this->assertSame($email, $this->content['email'] ?? null);
    }

    public function testSignupDuplicateEmail(): void
    {
        $this->client->request(
            'POST',
            '/signup',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            <<<'JSON'
{
    "email": "contact@example.com",
    "password": "StrongPassword*"
}
JSON
        );
        $this->assertResponseCode(409);
    }

    public function testBadSignin(): void
    {
        $this->client->request(
            'POST',
            '/signin',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            <<<'JSON'
{
    "username": "contact@example.com",
    "password": "InvalidPassword*"
}
JSON
        );
        $this->assertResponseCode(401);
    }

    protected function assertJsonResponse(): void
    {
        $response = $this->client->getResponse();
        $this->content = json_decode($response->getContent(), true, 50);
        $this->assertIsArray($this->content);
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            (string) $response->headers
        );
    }

    protected function assertResponseCode(int $code): void
    {
        $this->assertSame($code, $this->client->getResponse()->getStatusCode());
    }

    private function ensureSigninUserExists(): void
    {
        /** @var EntityManagerInterface $em */
        $em = static::getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository(User::class);
        $user = $repo->findOneBy(['email' => 'contact@example.com']);
        if ($user instanceof User) {
            return;
        }

        $user = new User();
        $user->setEmail('contact@example.com');
        /** @var UserPasswordHasherInterface $hasher */
        $hasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $user->setPassword($hasher->hashPassword($user, 'StrongPassword*'));
        $user->setRoles(['ROLE_ADMIN']);
        $em->persist($user);
        $em->flush();
    }
}
