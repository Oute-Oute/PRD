<?php

namespace App\Repository;

use App\Entity\Indisponibilities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Indisponibilities>
 *
 * @method Indisponibilities|null find($id, $lockMode = null, $lockVersion = null)
 * @method Indisponibilities|null findOneBy(array $criteria, array $orderBy = null)
 * @method Indisponibilities[]    findAll()
 * @method Indisponibilities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndisponibilitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Indisponibilities::class);
    }

    public function add(Indisponibilities $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Indisponibilities $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Indisponibilities[] Returns an array of Indisponibilities objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Indisponibilities
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
