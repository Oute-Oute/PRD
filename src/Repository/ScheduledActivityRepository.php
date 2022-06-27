<?php

namespace App\Repository;

use App\Entity\ScheduledActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function findByschedulerActivitiesByDate($TodayDate): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere($this->expr()->like('s.startdatetime = :date'))
            ->setParameter('date', $TodayDate)
            ->getQuery()
            ->getResult()
        ;
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
