<?php

namespace App\Repository;

use App\Entity\Clase;
use App\Entity\Monitor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Clase>
 *
 * @method Clase|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clase|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clase[]    findAll()
 * @method Clase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clase::class);
    }

    /**
     * @return Clase[] Returns an array of Monitor objects
     */
    public function findByFecha($fecha)
    {
        $sql = 'select c.* from safagym.clase c where date(c.fecha) = :fecha ';

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Clase::class, 'm');
        $query = $this->getEntityManager()->createNativeQuery($sql,$rsm);
        $query->setParameter("fecha", $fecha);

        $result = $query->getResult();

        return $result;
    }

//    public function findOneBySomeField($value): ?Clase
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
