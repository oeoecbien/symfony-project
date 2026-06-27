<?php

namespace App\Twig\Components;

use App\Entity\Character;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class CharacterCard
{
    public Character $character;

    public bool $showActions = true;
}
