<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Building;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\Component\HttpFoundation\InputBag;

interface BuildingServiceInterface
{
    public function create(string $data): Building;

    public function serializeJson(mixed $object): string;

    public function submit(Building $building, string $formName, string|array $data): void;

    public function isEntityFilled(Building $building): void;

    public function update(Building $building, string $data): void;

    public function delete(Building $building): void;

    /**
     * @return array<int, Building>
     */
    public function findAll(): array;

    public function findAllPaginated(InputBag $query): SlidingPagination;

    public function setLinks(mixed $object): void;

    /**
     * @return array<int, string>
     */
    public function getImages(int $number): array;
}
