<?php

namespace App\Repository;

use App\Entity\ActivityMaterialResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivityMaterialResource>
 *
 * @method ActivityMaterialResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityMaterialResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityMaterialResource[]    findAll()
 * @method ActivityMaterialResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityMaterialResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityMaterialResource::class);
    }

    public function add(ActivityMaterialResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ActivityMaterialResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return ActivityMaterialResourceCategory[] Returns an array of MaterialResourceCategory objects
    */
    public function findActivitiesByMaterialResourceCategory($id): array
    {
        $qb= $this->createQueryBuilder('a')
        ->join('a.activity','activity')
        ->join('activity.pathway','pathway')
        ->select('a.id, a.quantity, activity.activityname, pathway.pathwayname')
        ->orderBy('pathway.pathwayname', 'ASC')
        ->addOrderBy('activity.activityname', 'ASC')
        ->where('a.materialresourcecategory= :idMaterialResouceCategory')
        ->setParameter('idMaterialResouceCategory',$id);
        $query=$qb->getQuery()->getResult(); 
        return $query;
    } 

//    /**
//     * @return ActivityMaterialResource[] Returns an array of ActivityMaterialResource objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ActivityMaterialResource
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
