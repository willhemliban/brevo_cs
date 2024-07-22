<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

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

    public function getList(Request $request)
    {
        // Retrieve sorting parameter from the request
        $sortBy = $request->query->get('sort', 'totalEmailSent');
        $sortDirection = $request->query->get('direction', 'DESC');

        // Retrieve filtering params from the request
        $filterCategory = $request->query->get('category', null);
        $filterClientId = $request->query->get('client_id', null);

        $queryBuilder = $this->createQueryBuilder('client');

        if ($filterClientId) {
            $queryBuilder->andWhere('client.clientId = :clientId')
                ->setParameter('clientId', $filterClientId);
        }

        if ($filterCategory > -1) {
            $queryBuilder->andWhere('client.category = :category')
                ->setParameter('category', $filterCategory);
        }

        $queryBuilder->orderBy("client.$sortBy", $sortDirection ?: 'DESC');

        $clients = $queryBuilder->getQuery()->getResult();

        return $clients;

    }

    public function getTypicalClients()
    {
        $category1 = $this->createQueryBuilder('client')
        ->select('AVG(records.totalSent) as avgTotalSent,
            AVG(records.openRate) as avgOpenRate,
            AVG(records.unsubscriptionRate) as avgUnsubscriptionRate,
            AVG(records.bounceRate) as avgBounceRate,
            AVG(records.complaintRate) as avgComplaintRate'
        )
        ->leftJoin('client.records', 'records')
        ->andWhere('client.category = 0')
        ->getQuery()->getSingleResult();

        $category2 = $this->createQueryBuilder('client')
        ->select('AVG(records.totalSent) as avgTotalSent,
            AVG(records.openRate) as avgOpenRate,
            AVG(records.unsubscriptionRate) as avgUnsubscriptionRate,
            AVG(records.bounceRate) as avgBounceRate,
            AVG(records.complaintRate) as avgComplaintRate'
        )
        ->leftJoin('client.records', 'records')
        ->andWhere('client.category = 1')
        ->getQuery()->getSingleResult();

        $category3 = $this->createQueryBuilder('client')
        ->select('AVG(records.totalSent) as avgTotalSent,
            AVG(records.openRate) as avgOpenRate,
            AVG(records.unsubscriptionRate) as avgUnsubscriptionRate,
            AVG(records.bounceRate) as avgBounceRate,
            AVG(records.complaintRate) as avgComplaintRate'
        )
        ->leftJoin('client.records', 'records')
        ->andWhere('client.category = 2')
        ->getQuery()->getSingleResult();

        $typicalClients = [
            1 => $category1,
            2 => $category2,
            3 => $category3
        ];

        return $typicalClients;

    }

}