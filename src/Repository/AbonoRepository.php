<?php

namespace App\Repository;

use App\Entity\Abono;
use App\Entity\Cliente;
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


    public function findAbonoActual(Cliente $cliente): ?Abono
    {
        $fechaActual = new \DateTime();

        return $this->createQueryBuilder('a')
            ->andWhere(':fechaActual BETWEEN a.fechaInicio AND a.fechaCaducidad AND a.cliente = :cliente')
            ->setParameter('fechaActual', $fechaActual)
            ->setParameter('cliente', $cliente)
            ->orderBy('a.fechaInicio', 'asc')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findUltimoContratado(Cliente $cliente): ?Abono
    {
        $fechaActual = new \DateTime();

        return $this->createQueryBuilder('a')
            ->andWhere(':fechaActual >= a.fechaCaducidad AND a.fechaInicio < :fechaActual AND a.cliente = :cliente')
            ->setParameter('fechaActual', $fechaActual)
            ->setParameter('cliente', $cliente)
            ->orderBy('a.fechaInicio', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

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
