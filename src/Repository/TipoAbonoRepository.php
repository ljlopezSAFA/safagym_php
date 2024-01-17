<?php

namespace App\Repository;

use App\Entity\TipoAbono;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoAbono>
 *
 * @method TipoAbono|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoAbono|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoAbono[]    findAll()
 * @method TipoAbono[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoAbonoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoAbono::class);
    }

//    /**
//     * @return TipoAbono[] Returns an array of TipoAbono objects
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

//    public function findOneBySomeField($value): ?TipoAbono
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
