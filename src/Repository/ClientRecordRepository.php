<?php

namespace App\Repository;

use App\Entity\ClientRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientRecord[]    findAll()
 * @method ClientRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientRecord::class);
    }
}