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

    public function findSchedulerActivitiesByDate($date)
    {
        $qb = $this->createQueryBuilder('sa')
            ->join('sa.appointment', 'a')
            ->where('a.dayappointment = :date')
            ->setParameter('date', $date);
        $query = $qb->getQuery()->getResult();
        return $query;
    }

    public function deleteALl()
    {
        $qb = $this->createQueryBuilder('sa')
            ->delete();
        $query = $qb->getQuery()->getResult();
        return $query;
    }

    public function setFromArray(array $data, ManagerRegistry $registry)
    {
        $scheduledActivity = new ScheduledActivity();
        $appointment = $registry->getRepository('App\Entity\Appointment')->find($data['appointment']['id']);
        $scheduledActivity->setAppointment($appointment);
        $activity = $registry->getRepository('App\Entity\Activity')->find($data['activity']['id']);
        $scheduledActivity->setActivity($activity);
        $startTime = new \DateTime($data['starttime']);
        $scheduledActivity->setStarttime($startTime);
        $endTime = new \DateTime($data['endtime']);
        $scheduledActivity->setEndtime($endTime);
        $this->add($scheduledActivity, true);
        if ($scheduledActivity->getId() == NULL) {
            $SA = new $scheduledActivity();
            $SA->setAppointment($appointment);
            $SA->setActivity($activity);
            $SA->setStarttime($startTime);
            $SA->setEndtime($endTime);
            $SA->setId($data['id']);
            $this->add($SA, true);
            $scheduledActivity = $SA;
        }
        $this->changeId($scheduledActivity->getId(), $data['id']);
    }


    public function changeId($oldId, $newId)
    {
        $qb = $this->createQueryBuilder('sa')
            ->update()
            ->set('sa.id', ':newId')
            ->where('sa.id = :oldId')
            ->setParameter('newId', $newId)
            ->setParameter('oldId', $oldId);
        $query = $qb->getQuery()->getResult();

        $this->getEntityManager()->getConnection()->exec("UPDATE sqlite_sequence SET seq = $newId+1 WHERE name = '" . 'scheduled_activity' . "'");

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