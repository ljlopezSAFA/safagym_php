<?php

namespace App\Repository;

use App\Entity\TipoMonitor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoMonitor>
 *
 * @method TipoMonitor|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoMonitor|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoMonitor[]    findAll()
 * @method TipoMonitor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoMonitorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoMonitor::class);
    }

//    /**
//     * @return TipoMonitor[] Returns an array of TipoMonitor objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TipoMonitor
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
