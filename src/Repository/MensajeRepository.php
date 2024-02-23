<?php

namespace App\Repository;

use App\Entity\Mensaje;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mensaje>
 *
 * @method Mensaje|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mensaje|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mensaje[]    findAll()
 * @method Mensaje[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MensajeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mensaje::class);
    }

    /**
     * @return Mensaje[] Returns an array of Mensaje objects
     */
    public function findByRecetorEmisor($receptor, $emisor, $limit, $offset): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('(m.receptor = :receptor and m.emisor = :emisor) or (m.receptor = :emisor and m.emisor = :receptor) ')
            ->setParameter(':receptor', $receptor)
            ->setParameter(':emisor', $emisor)
            ->orderBy('m.fecha', 'desc')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Mensaje
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
