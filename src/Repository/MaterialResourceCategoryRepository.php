<?php

namespace App\Repository;

use App\Entity\MaterialResourceCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MaterialResourceCategory>
 *
 * @method MaterialResourceCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialResourceCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialResourceCategory[]    findAll()
 * @method MaterialResourceCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialResourceCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterialResourceCategory::class);
    }

    public function add(MaterialResourceCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MaterialResourceCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return MaterialResourceCategory[] Returns an array of MaterialResourceCategory objects
    */
    public function findMaterialCategoriesSorted(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('LOWER(c.categoryname)')
            ->getQuery()
            ->getResult();
    } 

//    /**
//     * @return MaterialResourceCategory[] Returns an array of MaterialResourceCategory objects
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

//    public function findOneBySomeField($value): ?MaterialResourceCategory
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
