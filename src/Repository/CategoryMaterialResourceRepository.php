<?php

namespace App\Repository;

use App\Entity\CategoryMaterialResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryMaterialResource>
 *
 * @method CategoryMaterialResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryMaterialResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryMaterialResource[]    findAll()
 * @method CategoryMaterialResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryMaterialResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryMaterialResource::class);
    }

    public function add(CategoryMaterialResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CategoryMaterialResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CategoryMaterialResource[] Returns an array of CategoryMaterialResource objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CategoryMaterialResource
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
