<?php

namespace App\Repository;

use App\Entity\CategoryOfHumanResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryOfHumanResource>
 *
 * @method CategoryOfHumanResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryOfHumanResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryOfHumanResource[]    findAll()
 * @method CategoryOfHumanResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryOfHumanResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryOfHumanResource::class);
    }

    public function add(CategoryOfHumanResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CategoryOfHumanResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return HumanResource[] Returns an array of HumanResource objects
    */
    public function findHumanResourceByCategory($idCategory)
    {
        $qb= $this->createQueryBuilder('c')
        ->join('c.humanresource','humanresource')
        ->select('humanresource.humanresourcename')
        ->orderBy('humanresource.humanresourcename', 'ASC')
        ->where('c.humanresourcecategory= :idCategory')
        ->setParameter('idCategory',$idCategory);
        $query=$qb->getQuery()->getResult(); 
        return $query;
    } 

//    /**
//     * @return CategoryOfHumanResource[] Returns an array of CategoryOfHumanResource objects
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

//    public function findOneBySomeField($value): ?CategoryOfHumanResource
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
