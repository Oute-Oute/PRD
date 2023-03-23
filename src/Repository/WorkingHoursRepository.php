<?php

namespace App\Repository;

use App\Entity\WorkingHours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkingHours>
 *
 * @method WorkingHours|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkingHours|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkingHours[]    findAll()
 * @method WorkingHours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkingHoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkingHours::class);
    }

    public function add(WorkingHours $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WorkingHours $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function deleteALl(): void
    {
        $this->getEntityManager()->createQuery('DELETE FROM App\Entity\WorkingHours')->execute();
    }

    public function setFromArray(array $data, ManagerRegistry $registry)
    {
        $workingHours = new WorkingHours();
        $workingHours->setDayweek($data['dayweek']);
        $startTime = new \DateTime($data['starttime']);
        $workingHours->setStarttime($startTime);
        $endTime = new \DateTime($data['endtime']);
        $workingHours->setEndtime($endTime);
        $workingHours->setHumanresource($registry->getRepository('App\Entity\HumanResource')->find($data['humanresource']['id']));
        $this->add($workingHours, true);
        if ($workingHours->getId() == NULL) {
            $WH = new WorkingHours();
            $WH->setDayweek($data['dayweek']);
            $WH->setStarttime($startTime);
            $WH->setEndtime($endTime);
            $WH->setHumanresource($registry->getRepository('App\Entity\HumanResource')->find($data['humanresource']['id']));
            $WH->setId($data['id']);
            $this->add($WH, true);
            $workingHours = $WH;
        }
        $this->changeId($workingHours->getId(), $data['id']);
    }

    public function changeId($oldId, $newId): void
    {
        $this->getEntityManager()->createQuery('UPDATE App\Entity\WorkingHours w SET w.id = :newId WHERE w.id = :oldId')
            ->setParameter('newId', $newId)
            ->setParameter('oldId', $oldId)
            ->execute();

        $this->getEntityManager()->getConnection()->exec("UPDATE sqlite_sequence SET seq = $newId+1 WHERE name = '" . 'working_hours' . "'");
    }

//    /**
//     * @return WorkingHours[] Returns an array of WorkingHours objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WorkingHours
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}