<?php

namespace App\Repository;

use App\Entity\UnavailabilityHumanResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UnavailabilityHumanResource>
 *
 * @method UnavailabilityHumanResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method UnavailabilityHumanResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method UnavailabilityHumanResource[]    findAll()
 * @method UnavailabilityHumanResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnavailabilityHumanResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnavailabilityHumanResource::class);
    }

    public function add(UnavailabilityHumanResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UnavailabilityHumanResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function deleteALl(): void
    {
        $this->getEntityManager()->createQuery('DELETE FROM App\Entity\UnavailabilityHumanResource')->execute();
    }

//    /**
//     * @return UnavailabilityHumanResource[] Returns an array of UnavailabilityHumanResource objects
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

//    public function findOneBySomeField($value): ?UnavailabilityHumanResource
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}