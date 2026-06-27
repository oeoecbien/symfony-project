<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Button
{
    public string $label = 'Cliquer';

    public string $type = 'primary';

    public ?string $url = null;
}
