<?php

namespace App\Repository;

use App\Entity\UnavailabilityMaterialResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UnavailabilityMaterialResource>
 *
 * @method UnavailabilityMaterialResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method UnavailabilityMaterialResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method UnavailabilityMaterialResource[]    findAll()
 * @method UnavailabilityMaterialResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnavailabilityMaterialResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnavailabilityMaterialResource::class);
    }

    public function add(UnavailabilityMaterialResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UnavailabilityMaterialResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function deleteALl(): void
    {
        $this->getEntityManager()->createQuery('DELETE FROM App\Entity\UnavailabilityMaterialResource')->execute();
    }

//    /**
//     * @return UnavailabilityMaterialResource[] Returns an array of UnavailabilityMaterialResource objects
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

//    public function findOneBySomeField($value): ?UnavailabilityMaterialResource
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}