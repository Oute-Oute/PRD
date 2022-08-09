<?php

namespace App\Repository;

use App\Entity\HumanResourceCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HumanResourceCategory>
 *
 * @method HumanResourceCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method HumanResourceCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method HumanResourceCategory[]    findAll()
 * @method HumanResourceCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HumanResourceCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HumanResourceCategory::class);
    }

    public function add(HumanResourceCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HumanResourceCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return HumanResourceCategory[] Returns an array of HumanResourceCategory objects
    */
    public function findHumanCategoriesSorted(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('LOWER(c.categoryname)')
            ->getQuery()
            ->getResult();
    } 

//    /**
//     * @return HumanResourceCategory[] Returns an array of HumanResourceCategory objects
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

//    public function findOneBySomeField($value): ?HumanResourceCategory
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
