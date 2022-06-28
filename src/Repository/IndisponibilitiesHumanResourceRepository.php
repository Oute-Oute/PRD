<?php

namespace App\Repository;

use App\Entity\IndisponibilitiesHumanResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IndisponibilitiesHumanResource>
 *
 * @method IndisponibilitiesHumanResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndisponibilitiesHumanResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndisponibilitiesHumanResource[]    findAll()
 * @method IndisponibilitiesHumanResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndisponibilitiesHumanResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndisponibilitiesHumanResource::class);
    }

    public function add(IndisponibilitiesHumanResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(IndisponibilitiesHumanResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return IndisponibilitiesHumanResource[] Returns an array of IndisponibilitiesHumanResource objects
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

//    public function findOneBySomeField($value): ?IndisponibilitiesHumanResource
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
