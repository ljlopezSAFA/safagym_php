<?php

namespace App\Repository;

use App\Entity\Abono;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Abono>
 *
 * @method Abono|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abono|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abono[]    findAll()
 * @method Abono[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abono::class);
    }

//    /**
//     * @return Abono[] Returns an array of Abono objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Abono
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
