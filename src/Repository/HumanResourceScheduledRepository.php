<?php

namespace App\Repository;

use App\Entity\HumanResourceScheduled;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HumanResourceScheduled>
 *
 * @method HumanResourceScheduled|null find($id, $lockMode = null, $lockVersion = null)
 * @method HumanResourceScheduled|null findOneBy(array $criteria, array $orderBy = null)
 * @method HumanResourceScheduled[]    findAll()
 * @method HumanResourceScheduled[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HumanResourceScheduledRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HumanResourceScheduled::class);
    }

    public function add(HumanResourceScheduled $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HumanResourceScheduled $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAppointmentsByHumanResource($id, $date): array
    {
        $qb = $this->createQueryBuilder('h')
            ->join('h.scheduledactivity', 'scheduledactivity')
            ->join('scheduledactivity.appointment', 'appointment')
            ->join('appointment.patient', 'patient')
            ->join('appointment.pathway', 'pathway')
            ->select('appointment.dayappointment, patient.lastname, patient.firstname, pathway.pathwayname')
            ->orderBy('appointment.dayappointment', 'ASC')
            ->addOrderBy('patient.lastname', 'ASC')
            ->where('h.humanresource= :idHumanResource AND appointment.scheduled=1 AND appointment.dayappointment>= :date')
            ->distinct()
            ->setParameter('date', $date)
            ->setParameter('idHumanResource', $id);
        $query = $qb->getQuery()->getResult();
        return $query;
    }

    public function deleteALl()
    {
        $qb = $this->createQueryBuilder('h');
        $qb->delete();
        $qb->getQuery()->execute();
    }

    public function setFromArray(array $data, ManagerRegistry $registry)
    {
        $humanResourceScheduled = new HumanResourceScheduled();
        $humanResourceScheduled->setHumanresource($registry->getRepository('App\Entity\HumanResource')->find($data['humanresource']['id']));
        $humanResourceScheduled->setScheduledactivity($registry->getRepository('App\Entity\ScheduledActivity')->find($data['scheduledactivity']['id']));
        $this->add($humanResourceScheduled, true);
        $this->changeId($humanResourceScheduled->getId(), $data['id']);
    }

    public function changeId($oldId, $newId)
    {
        $qb = $this->createQueryBuilder('h');
        $q = $qb->update()
            ->set('h.id', $newId)
            ->where('h.id = :oldId')
            ->setParameter('oldId', $oldId)
            ->getQuery();
        $p = $q->execute();
    }

//    /**
//     * @return HumanResourceScheduled[] Returns an array of HumanResourceScheduled objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HumanResourceScheduled
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}