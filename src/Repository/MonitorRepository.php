<?php

namespace App\Repository;

use App\Entity\Monitor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Monitor>
 *
 * @method Monitor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Monitor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Monitor[]    findAll()
 * @method Monitor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonitorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Monitor::class);

    }

    /**
     * @return Monitor[] Returns an array of Monitor objects
     */
    public function findByTurno($id_turno): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.id_turno = :id_turno')
            ->setParameter('id_turno', $id_turno)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Monitor[] Returns an array of Monitor objects
     */
    public function findByTipos($tipos)
    {
        $sql = 'SELECT m.* FROM safagym.monitor m join safagym.monitor_tipo mt  on mt.id_monitor  = m.id where mt.id_tipo in (:tipos)';

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Monitor::class, 'm');
        $query = $this->getEntityManager()->createNativeQuery($sql,$rsm);
        $query->setParameter("tipos", $tipos, ArrayParameterType::INTEGER);

        $result = $query->getResult();

        return $result;
    }



//    public function findOneBySomeField($value): ?Monitor
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
