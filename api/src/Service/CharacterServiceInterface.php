<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Character;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\Component\HttpFoundation\InputBag;

interface CharacterServiceInterface
{
    public function create(string $data): Character;

    public function serializeJson(mixed $object): string;

    public function submit(Character $character, string $formName, string|array $data): void;

    public function isEntityFilled(Character $character): void;

    /**
     * @return array<int, Character>
     */
    public function findAll(): array;

    public function findAllPaginated(InputBag $query): SlidingPagination;

    public function findByMinLifePaginated(InputBag $query, int $life): SlidingPagination;

    /**
     * @return array<int, Character>
     */
    public function getAllByLifeLevel(int $level): array;

    /**
     * @return array<int, Character>
     */
    public function seedDefaults(): array;

    /**
     * Vide la table des personnages puis réinsère le jeu par défaut (identifiants auto consécutifs).
     *
     * @return array<int, Character>
     */
    public function resetAndSeed(): array;

    public function update(Character $character, string $data): void;

    public function delete(Character $character): void;

    public function setLinks(mixed $object): void;

    /**
     * @return array<int, string>
     */
    public function getImages(int $number, ?string $kind = null): array;

    /**
     * @return array<int, string>
     */
    public function getImagesKind(string $kind, int $number): array;
}
