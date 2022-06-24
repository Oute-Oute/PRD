<?php

namespace App\Repository;

use App\Entity\CompleteActivityResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompleteActivityResource>
 *
 * @method CompleteActivityResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompleteActivityResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompleteActivityResource[]    findAll()
 * @method CompleteActivityResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompleteActivityResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompleteActivityResource::class);
    }

    public function add(CompleteActivityResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CompleteActivityResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CompleteActivityResource[] Returns an array of CompleteActivityResource objects
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

//    public function findOneBySomeField($value): ?CompleteActivityResource
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
