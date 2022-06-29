<?php

namespace App\Repository;

use App\Entity\ScheduledActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Doctrine_Core; 

/**
 * @extends ServiceEntityRepository<ScheduledActivity>
 *
 * @method ScheduledActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScheduledActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScheduledActivity[]    findAll()
 * @method ScheduledActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduledActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScheduledActivity::class);
    }

    public function add(ScheduledActivity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ScheduledActivity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findSchedulerActivitiesByDate($TodayDate)
    {   
        $qb= $this->createQueryBuilder('s'); 
        $qb->select('s'); 
        $qb->where('s.dayscheduled=:date')
             ->setParameter('date',$TodayDate);
             $query=$qb->getQuery()->getResult(); 
             return $query;
    }

//    public function findOneBySomeField($value): ?ScheduledActivity
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
