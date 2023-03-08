<?php

namespace App\Repository;

use App\Entity\Appointment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointment>
 *
 * @method Appointment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointment[]    findAll()
 * @method Appointment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    public function add(Appointment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Appointment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Appointment[] Returns an array of Appointment objects
     */
    public function findAppointmentByDate($date)
    {
        $qb = $this->createQueryBuilder('a')
            ->join('a.patient', 'patient')
            ->join('a.pathway', 'pathway')
            ->select('a.id, a.dayappointment, a.earliestappointmenttime, a.latestappointmenttime, patient.lastname, patient.firstname, pathway.pathwayname')
            ->orderBy('patient.lastname', 'ASC')
            ->where('a.dayappointment= :date')
            ->setParameter('date', $date);
        $query = $qb->getQuery()->getResult();
        return $query;
    }

    /**
     * @return Appointment[] Returns an array of Appointment objects
     */
    public function findAppointmentByPathway($idPathway, $date)
    {
        $qb = $this->createQueryBuilder('a')
            ->join('a.patient', 'patient')
            ->select('a.id, a.dayappointment, a.earliestappointmenttime, a.latestappointmenttime, patient.lastname, patient.firstname')
            ->orderBy('a.dayappointment', 'ASC')
            ->addOrderBy('patient.lastname', 'ASC')
            ->where('a.pathway= :idPathway AND a.dayappointment>= :date')
            ->setParameter('date', $date)
            ->setParameter('idPathway', $idPathway);
        $query = $qb->getQuery()->getResult();
        return $query;
    }

    //    public function findOneBySomeField($value): ?Appointment
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function getNumberOfAppointmentByPathwayByDate($pathway, $date)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.dayappointment = :date')
            ->andWhere('a.pathway = :pathway')
            ->setParameter('date', $date)
            ->setParameter('pathway', $pathway);
        $query = $qb->getQuery()->getSingleScalarResult();
        return $query;
    }

    public function getAllAppointmentOrderByPatientLastname()
    {
        $qb = $this->createQueryBuilder('a')
            ->join('a.patient', 'patient')
            ->join('a.pathway', 'pathway')
            ->select('a.id, a.dayappointment, a.earliestappointmenttime, a.latestappointmenttime, patient.lastname, patient.firstname, pathway.pathwayname')
            ->orderBy('patient.lastname', 'ASC');
        $query = $qb->getQuery()->getResult();
        return $query;
    }

    public function deleteALl()
    {
        $qb = $this->createQueryBuilder('a')
            ->delete();
        $query = $qb->getQuery()->getResult();
        return $query;
    }
}