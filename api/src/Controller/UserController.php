<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserServiceInterface;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    public function __construct(
        private readonly UserServiceInterface $userService,
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/signup', name: 'app_signup', methods: ['POST'])]
    #[OA\RequestBody(
        request: 'UserSignup',
        description: 'Data for signup',
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            example: [
                'email' => 'newuser@example.com',
                'password' => 'StrongPassword*',
            ]
        )
    )]
    #[OA\Response(response: 201, description: 'User created')]
    #[OA\Response(response: 409, description: 'Email already used')]
    #[OA\Tag(name: 'User')]
    public function signup(Request $request): JsonResponse
    {
        /** @var array{email?: string, password?: string} $data */
        $data = json_decode($request->getContent(), true) ?? [];
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!is_string($email) || $email === '' || !is_string($password) || $password === '') {
            return new JsonResponse(['error' => 'Email and password are required'], Response::HTTP_BAD_REQUEST);
        }

        if ($this->userRepository->findOneByEmail($email) instanceof User) {
            return new JsonResponse(['error' => 'Email already used'], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $now = new DateTimeImmutable();
        $user->setCreation($now);
        $user->setModification($now);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['email' => $user->getEmail()], Response::HTTP_CREATED);
    }

    #[Route('/signin', name: 'app_signin', methods: ['POST'])]
    #[OA\RequestBody(
        request: 'User',
        description: 'Data for signin',
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            example: [
                'username' => 'contact@example.com',
                'password' => 'StrongPassword*',
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns a JWT',
        content: new OA\JsonContent(type: 'string')
    )]
    #[OA\Response(response: 404, description: 'Not found')]
    #[OA\Tag(name: 'User')]
    public function signin(): JsonResponse
    {
        /** @var User|null $user */
        $user = $this->getUser();
        if ($user === null) {
            return new JsonResponse(['error' => 'User not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['token' => $this->userService->getToken($user)]);
    }
}
