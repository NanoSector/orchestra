<?php


/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

namespace Domain\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\Entity\EndpointCollectionLog;

/**
 * @extends ServiceEntityRepository<EndpointCollectionLog>
 *
 * @method EndpointCollectionLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method EndpointCollectionLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method EndpointCollectionLog[]    findAll()
 * @method EndpointCollectionLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EndpointCollectionLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EndpointCollectionLog::class);
    }

    public function save(EndpointCollectionLog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EndpointCollectionLog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return EndpointCollectionLog[] Returns an array of EndpointCollectionLog objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EndpointCollectionLog
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
