<?php

namespace App\Controller;

use App\Service\ApiAuthentication;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SecurityController extends AbstractController
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ApiAuthentication $apiAuthentication,
    ) {
    }

    #[Route(path: '/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request): Response
    {
        if ($this->apiAuthentication->isAuthenticated($request->getSession())) {
            return $this->redirectToRoute('app_character_index');
        }

        if ($request->isMethod('POST')) {
            $username = $request->request->getString('_username');
            $password = $request->request->getString('_password');

            $response = $this->client->request(
                'POST',
                $this->getParameter('app.api_url').'/signin',
                [
                    'json' => [
                        'username' => $username,
                        'password' => $password,
                    ],
                ]
            );

            if (200 === $response->getStatusCode()) {
                /** @var array{token?: string} $content */
                $content = json_decode($response->getContent(), true) ?? [];
                if (!isset($content['token']) || !is_string($content['token']) || $content['token'] === '') {
                    $this->addFlash('danger', 'Reponse API invalide (token manquant).');

                    return $this->render('security/login.html.twig');
                }

                $this->apiAuthentication->store($request->getSession(), $content['token'], $username);

                return $this->redirectToRoute('app_character_index', [], Response::HTTP_SEE_OTHER);
            }

            $this->addFlash('danger', 'Identifiants incorrects.');
        }

        return $this->render('security/login.html.twig');
    }

    #[Route(path: '/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(Request $request): Response
    {
        $this->apiAuthentication->clear($request->getSession());

        return $this->redirectToRoute('app_login');
    }

    #[Route('/api-login', name: 'legacy_api_login', methods: ['GET'])]
    public function legacyApiLogin(): Response
    {
        return $this->redirectToRoute('app_login', [], Response::HTTP_PERMANENTLY_REDIRECT);
    }
}
