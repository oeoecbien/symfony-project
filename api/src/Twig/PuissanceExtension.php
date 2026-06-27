<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PuissanceExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('puissance', [$this, 'puissance']),
        ];
    }

    /**
     * @param array<string, mixed> $character
     */
    public function puissance(array $character): int
    {
        $coefficients = [
            'Dame' => 1.5,
            'Tourmenteuse' => 1.4,
            'Seigneur' => 1.3,
            'Tourmenteur' => 1.2,
        ];

        $strength = (int) ($character['strength'] ?? 0);
        $intelligence = (int) ($character['intelligence'] ?? 0);
        $kind = (string) ($character['kind'] ?? '');
        $coefficient = $coefficients[$kind] ?? 1.0;

        return (int) round($strength * $intelligence * $coefficient);
    }
}
