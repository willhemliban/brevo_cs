<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function findMaxEmailSent(): int
    {
        $qb = $this->createQueryBuilder('client')
            ->select('MAX(client.totalEmailSent) as maxEmailSent')
            ->getQuery()
            ->getSingleScalarResult();

        return $qb;
    }

    public function findAllOrderedByEmailsSent()
    {
        return $this->createQueryBuilder('client')
            ->orderBy('client.totalEmailSent', 'DESC')
            ->getQuery()
            ->getResult();
    }

}