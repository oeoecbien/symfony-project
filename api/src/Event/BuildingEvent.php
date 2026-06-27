<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Building;
use Symfony\Contracts\EventDispatcher\Event;

final class BuildingEvent extends Event
{
    public const BUILDING_CREATED = 'app.building.created';
    public const BUILDING_UPDATED = 'app.building.updated';
    public const BUILDING_CREATED_POST_DATABASE = 'app.building.created.post_database';

    public function __construct(
        private readonly Building $building,
    ) {
    }

    public function getBuilding(): Building
    {
        return $this->building;
    }
}
