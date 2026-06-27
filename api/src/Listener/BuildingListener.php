<?php

declare(strict_types=1);

namespace App\Listener;

use App\Event\BuildingEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class BuildingListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BuildingEvent::BUILDING_CREATED => 'onBuildingCreated',
            BuildingEvent::BUILDING_UPDATED => 'onBuildingUpdated',
            BuildingEvent::BUILDING_CREATED_POST_DATABASE => 'onBuildingCreatedPostDatabase',
        ];
    }

    public function onBuildingCreated(BuildingEvent $event): void
    {
        // Event hook intentionally left without mutation for this sequence step.
        $event->getBuilding();
    }

    public function onBuildingCreatedPostDatabase(BuildingEvent $event): void
    {
        // Event hook intentionally left without mutation for this sequence step.
        $event->getBuilding();
    }

    public function onBuildingUpdated(BuildingEvent $event): void
    {
        $building = $event->getBuilding();
        $currentStrength = $building->getStrength() ?? 0;
        $building->setStrength($currentStrength - 20);
    }
}
