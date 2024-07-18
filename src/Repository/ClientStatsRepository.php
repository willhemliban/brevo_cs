<?php

namespace App\Repository;

use App\Entity\ClientStats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientStats|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientStats|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientStats[]    findAll()
 * @method ClientStats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientStatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientStats::class);
    }
}