<?php

namespace App\Repository;

use App\Entity\Character;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Character>
 */
class CharacterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Character::class);
    }

    /**
     * @return array<int, Character>
     */
    public function getAllByLifeLevel(int $level): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.life >= :level')
            ->setParameter('level', $level)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
