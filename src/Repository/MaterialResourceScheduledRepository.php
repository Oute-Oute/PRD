<?php

namespace App\Repository;

use App\Entity\MaterialResourceScheduled;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MaterialResourceScheduled>
 *
 * @method MaterialResourceScheduled|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialResourceScheduled|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialResourceScheduled[]    findAll()
 * @method MaterialResourceScheduled[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialResourceScheduledRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterialResourceScheduled::class);
    }

    public function add(MaterialResourceScheduled $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MaterialResourceScheduled $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function deleteALl()
    {
        $qb = $this->createQueryBuilder('m');
        $qb->delete();
        $query = $qb->getQuery()->getResult();
        return $query;
    }

    public function findAppointmentsByMaterialResource($id, $date): array
    {
        $qb = $this->createQueryBuilder('m')
            ->join('m.scheduledactivity', 'scheduledactivity')
            ->join('scheduledactivity.appointment', 'appointment')
            ->join('appointment.patient', 'patient')
            ->join('appointment.pathway', 'pathway')
            ->select('appointment.dayappointment, patient.lastname, patient.firstname, pathway.pathwayname')
            ->orderBy('appointment.dayappointment', 'ASC')
            ->addOrderBy('patient.lastname', 'ASC')
            ->where('m.materialresource= :idMaterialResource AND appointment.scheduled=1 AND appointment.dayappointment>= :date')
            ->distinct()
            ->setParameter('date', $date)
            ->setParameter('idMaterialResource', $id);
        $query = $qb->getQuery()->getResult();
        return $query;
    }

    public function setFromArray(array $data, ManagerRegistry $registry)
    {
        $materialResourceScheduled = new MaterialResourceScheduled();
        $materialResourceScheduled->setMaterialresource($registry->getRepository('App\Entity\MaterialResource')->find($data['materialresource']['id']));
        $materialResourceScheduled->setScheduledactivity($registry->getRepository('App\Entity\ScheduledActivity')->find($data['scheduledactivity']['id']));
        $this->add($materialResourceScheduled, true);
        if ($materialResourceScheduled->getId() == NULL) {
            $MRS = new $materialResourceScheduled();
            $MRS->setMaterialresource($registry->getRepository('App\Entity\MaterialResource')->find($data['materialresource']['id']));
            $MRS->setScheduledactivity($registry->getRepository('App\Entity\ScheduledActivity')->find($data['scheduledactivity']['id']));
            $MRS->setId($data['id']);
            $this->add($MRS, true);
            $materialResourceScheduled = $MRS;
        }
        $this->changeId($materialResourceScheduled->getId(), $data['id']);
        return $materialResourceScheduled;
    }

    public function changeId($oldId, $newId)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->update();
        $qb->set('m.id', $qb->expr()->literal($newId));
        $qb->where('m.id = :oldId');
        $qb->setParameter('oldId', $oldId);
        $query = $qb->getQuery()->getResult();

        $this->getEntityManager()->getConnection()->exec("UPDATE sqlite_sequence SET seq = $newId WHERE name = '" . 'material_resource_scheduled' . "'");

        return $query;
    }

//    /**
//     * @return MaterialResourceScheduled[] Returns an array of MaterialResourceScheduled objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MaterialResourceScheduled
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}