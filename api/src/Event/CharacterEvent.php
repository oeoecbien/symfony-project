<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Character;
use Symfony\Contracts\EventDispatcher\Event;

final class CharacterEvent extends Event
{
    public const CHARACTER_CREATED = 'app.character.created';
    public const CHARACTER_UPDATED = 'app.character.updated';
    public const CHARACTER_CREATED_POST_DATABASE = 'app.character.created.post_database';

    public function __construct(
        private readonly Character $character,
    ) {
    }

    public function getCharacter(): Character
    {
        return $this->character;
    }
}
