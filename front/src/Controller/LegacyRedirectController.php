<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LegacyRedirectController extends AbstractController
{
    #[Route('/characters', name: 'legacy_characters_index')]
    #[Route('/characters/{path}', name: 'legacy_characters_path', requirements: ['path' => '.+'])]
    public function characters(?string $path = null): Response
    {
        if ($path !== null && $path !== '') {
            return $this->redirect('/api-character/'.$path, Response::HTTP_PERMANENTLY_REDIRECT);
        }

        return $this->redirectToRoute('app_character_index', [], Response::HTTP_PERMANENTLY_REDIRECT);
    }
}