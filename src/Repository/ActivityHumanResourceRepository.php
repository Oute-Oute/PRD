<?php

namespace App\Repository;

use App\Entity\ActivityHumanResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivityHumanResource>
 *
 * @method ActivityHumanResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityHumanResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityHumanResource[]    findAll()
 * @method ActivityHumanResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityHumanResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityHumanResource::class);
    }

    public function add(ActivityHumanResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ActivityHumanResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return ActivityHumanResourceCategory[] Returns an array of ActivityHumanResourceCategory objects
    */
    public function findActivitiesByHumanResourceCategory($id): array
    {
        $qb= $this->createQueryBuilder('a')
        ->join('a.activity','activity')
        ->join('activity.pathway','pathway')
        ->select('a.id, a.quantity, activity.activityname, pathway.pathwayname')
        ->orderBy('pathway.pathwayname', 'ASC')
        ->addOrderBy('activity.activityname', 'ASC')
        ->where('a.humanresourcecategory= :idHumanResouceCategory')
        ->setParameter('idHumanResouceCategory',$id);
        $query=$qb->getQuery()->getResult(); 
        return $query;
    } 

//    /**
//     * @return ActivityHumanResource[] Returns an array of ActivityHumanResource objects
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

//    public function findOneBySomeField($value): ?ActivityHumanResource
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
