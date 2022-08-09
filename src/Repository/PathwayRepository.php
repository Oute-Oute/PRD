<?php

namespace App\Repository;

use App\Entity\Pathway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pathway>
 *
 * @method Pathway|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pathway|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pathway[]    findAll()
 * @method Pathway[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PathwayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pathway::class);
    }

    public function add(Pathway $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Pathway $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Pathway[] Returns an array of Pathway objects
    */
   public function findAllPathway(): array
   {
       return $this->createQueryBuilder('p')
           ->orderBy('LOWER(p.pathwayname)', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?Pathway
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
