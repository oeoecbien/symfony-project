<?php

declare(strict_types=1);

namespace App\Listener;

use App\Event\CharacterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class CharacterListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            CharacterEvent::CHARACTER_CREATED => 'onCharacterCreated',
            CharacterEvent::CHARACTER_UPDATED => 'onCharacterUpdated',
            CharacterEvent::CHARACTER_CREATED_POST_DATABASE => 'onCharacterCreatedPostDatabase',
        ];
    }

    public function onCharacterCreated(CharacterEvent $event): void
    {
        // Event hook intentionally left without mutation for this sequence step.
        $event->getCharacter();
    }

    public function onCharacterUpdated(CharacterEvent $event): void
    {
        // Event hook intentionally left without mutation for this sequence step.
        $event->getCharacter();
    }

    public function onCharacterCreatedPostDatabase(CharacterEvent $event): void
    {
        // Event hook intentionally left without mutation for this sequence step.
        $event->getCharacter();
    }
}
