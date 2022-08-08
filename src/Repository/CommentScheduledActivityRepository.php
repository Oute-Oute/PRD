<?php

namespace App\Repository;

use App\Entity\CommentScheduledActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommentScheduledActivity>
 *
 * @method CommentScheduledActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentScheduledActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentScheduledActivity[]    findAll()
 * @method CommentScheduledActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentScheduledActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentScheduledActivity::class);
    }

    public function add(CommentScheduledActivity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CommentScheduledActivity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CommentScheduledActivity[] Returns an array of CommentScheduledActivity objects
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

//    public function findOneBySomeField($value): ?CommentScheduledActivity
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
