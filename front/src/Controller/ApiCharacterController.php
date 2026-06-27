<?php

namespace App\Controller;

use App\Form\ApiCharacterType;
use App\Service\ApiAuthentication;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/api-character')]
final class ApiCharacterController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
        private ApiAuthentication $apiAuthentication,
    ) {
    }

    #[Route(name: 'app_character_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $token = $this->requireToken($request);
        if ($token instanceof Response) {
            return $token;
        }

        $path = '/characters/?size=50';
        $life = $request->query->getInt('life', -1);
        if ($life >= 0) {
            $path .= '&life='.$life;
        }

        $response = $this->requestApi($request, 'GET', $path, $token);

        if ($response instanceof Response) {
            return $response;
        }

        return $this->render('api-character/index.html.twig', [
            'characters' => $response->toArray(),
        ]);
    }

    #[Route('/life/{level}', name: 'api_character_life', requirements: ['level' => '^([0-9]{1,3})$'], methods: ['GET'])]
    public function lifeLevel(Request $request, int $level): Response
    {
        $token = $this->requireToken($request);
        if ($token instanceof Response) {
            return $token;
        }

        $response = $this->requestApi($request, 'GET', '/characters/life/'.$level, $token);

        if ($response instanceof Response) {
            return $response;
        }

        return $this->render('api-character/index.html.twig', [
            'characters' => $response->toArray(),
        ]);
    }

    #[Route('/new', name: 'app_character_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $token = $this->requireToken($request);
        if ($token instanceof Response) {
            return $token;
        }

        $character = [];
        $form = $this->createForm(ApiCharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $this->extractCharacterData($request);
            $apiResponse = $this->requestApi($request, 'POST', '/characters/', $token, ['json' => $data]);

            if ($apiResponse instanceof Response) {
                return $apiResponse;
            }

            return $this->redirectToRoute('app_character_show', [
                'identifier' => $apiResponse->toArray()['identifier'],
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('api-character/new.html.twig', [
            'character' => $character,
            'form' => $form,
        ]);
    }

    #[Route('/{identifier}', name: 'app_character_show', methods: ['GET'])]
    public function show(Request $request, string $identifier): Response
    {
        $token = $this->requireToken($request);
        if ($token instanceof Response) {
            return $token;
        }

        $response = $this->requestApi($request, 'GET', '/characters/'.$identifier, $token);

        if ($response instanceof Response) {
            return $response;
        }

        return $this->render('api-character/show.html.twig', [
            'character' => $response->toArray(),
        ]);
    }

    #[Route('/{identifier}/edit', name: 'app_character_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, string $identifier): Response
    {
        $token = $this->requireToken($request);
        if ($token instanceof Response) {
            return $token;
        }

        $getResponse = $this->requestApi($request, 'GET', '/characters/'.$identifier, $token);

        if ($getResponse instanceof Response) {
            return $getResponse;
        }

        $character = $getResponse->toArray();
        $form = $this->createForm(ApiCharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $this->extractCharacterData($request);
            $putResponse = $this->requestApi($request, 'PUT', '/characters/'.$identifier, $token, ['json' => $data]);

            if ($putResponse instanceof Response) {
                return $putResponse;
            }

            return $this->redirectToRoute('app_character_show', [
                'identifier' => $identifier,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('api-character/edit.html.twig', [
            'character' => $character,
            'form' => $form,
        ]);
    }

    #[Route('/{identifier}', name: 'app_character_delete', methods: ['POST'])]
    public function delete(Request $request, string $identifier): Response
    {
        $token = $this->requireToken($request);
        if ($token instanceof Response) {
            return $token;
        }

        if ($this->isCsrfTokenValid('delete'.$identifier, $request->request->get('_token'))) {
            $this->requestApi($request, 'DELETE', '/characters/'.$identifier, $token);
        }

        return $this->redirectToRoute('app_character_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @return array<string, mixed>
     */
    private function extractCharacterData(Request $request): array
    {
        /** @var array<string, mixed> $data */
        $data = $request->request->all()['api_character'] ?? [];
        unset($data['_token']);

        return $this->normalizeCharacterPayload($data);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function normalizeCharacterPayload(array $data): array
    {
        $normalized = [];
        foreach ($data as $key => $value) {
            if ($value === '' || $value === null) {
                continue;
            }
            if (\in_array($key, ['intelligence', 'strength', 'life'], true)) {
                $normalized[$key] = (int) $value;
                continue;
            }
            $normalized[$key] = \is_string($value) ? trim($value) : $value;
        }

        return $normalized;
    }

    private function requireToken(Request $request): string|Response
    {
        $token = $this->apiAuthentication->getTokenFromRequest($request);
        if (null === $token) {
            $this->addFlash('danger', 'Connectez-vous pour acceder aux personnages.');

            return $this->redirectToRoute('app_login');
        }

        return $token;
    }

    /**
     * @param array<string, mixed> $options
     */
    private function requestApi(
        Request $request,
        string $method,
        string $path,
        string $token,
        array $options = [],
    ): \Symfony\Contracts\HttpClient\ResponseInterface|Response {
        $options['auth_bearer'] = $token;

        try {
            return $this->client->request(
                $method,
                $this->getParameter('app.api_url').$path,
                $options
            );
        } catch (ClientExceptionInterface $e) {
            $status = $e->getResponse()->getStatusCode();

            if (401 === $status) {
                $this->apiAuthentication->clear($request->getSession());
                $this->addFlash('danger', 'Session expiree. Reconnectez-vous.');

                return $this->redirectToRoute('app_login');
            }

            if (422 === $status) {
                $detail = $e->getResponse()->getContent(false);
                $this->addFlash(
                    'danger',
                    'Donnees refusees par l API (422). Verifiez nom (3-20), type, surnom (3-50), image (/dames/xxx.webp, min. 5). Detail: '.substr($detail, 0, 300)
                );

                return $this->redirect($request->headers->get('referer') ?? $this->generateUrl('app_character_index'));
            }

            throw $e;
        }
    }
}
