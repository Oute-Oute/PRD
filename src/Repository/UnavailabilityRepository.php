<?php

namespace App\Repository;

use App\Entity\Unavailability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Unavailability>
 *
 * @method Unavailability|null find($id, $lockMode = null, $lockVersion = null)
 * @method Unavailability|null findOneBy(array $criteria, array $orderBy = null)
 * @method Unavailability[]    findAll()
 * @method Unavailability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnavailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Unavailability::class);
    }

    public function add(Unavailability $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Unavailability $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function deleteALl(): void
    {
        $this->getEntityManager()->createQuery('DELETE FROM App\Entity\Unavailability')->execute();
    }

    public function setFromArray(array $data, ManagerRegistry $registry)
    {
        $unavailability = new Unavailability();
        $startTime = new \DateTime($data['startdatetime']);
        $unavailability->setStartdatetime($startTime);
        $endTime = new \DateTime($data['enddatetime']);
        $unavailability->setEnddatetime($endTime);
        $this->add($unavailability, true);
        if ($unavailability->getId() == NULL) {
            $unavailability2 = new Unavailability();
            $unavailability2->setStartdatetime($startTime);
            $unavailability2->setEnddatetime($endTime);
            $unavailability2->setId($data['id']);
            $this->add($unavailability2, true);
            $unavailability = $unavailability2;
        }
        $this->changeId($unavailability->getId(), $data['id']);
    }

    public function changeId($oldId, $newId)
    {
        $this->createQueryBuilder('u')
            ->update()
            ->set('u.id', $newId)
            ->where('u.id = :oldId')
            ->setParameter('oldId', $oldId)
            ->getQuery()
            ->execute();
        $this->getEntityManager()->getConnection()->exec("UPDATE sqlite_sequence SET seq = $newId WHERE name = '" . 'unavailability' . "'");
    }

//    /**
//     * @return Unavailability[] Returns an array of Unavailability objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Unavailability
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}