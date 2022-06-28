<?php

namespace App\Repository;

use App\Entity\IndisponibilitiesMaterialResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IndisponibilitiesMaterialResource>
 *
 * @method IndisponibilitiesMaterialResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndisponibilitiesMaterialResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndisponibilitiesMaterialResource[]    findAll()
 * @method IndisponibilitiesMaterialResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndisponibilitiesMaterialResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndisponibilitiesMaterialResource::class);
    }

    public function add(IndisponibilitiesMaterialResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(IndisponibilitiesMaterialResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return IndisponibilitiesMaterialResource[] Returns an array of IndisponibilitiesMaterialResource objects
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

//    public function findOneBySomeField($value): ?IndisponibilitiesMaterialResource
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
