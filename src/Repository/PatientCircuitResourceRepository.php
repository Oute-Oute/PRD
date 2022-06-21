<?php

namespace App\Repository;

use App\Entity\PatientCircuitResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PatientCircuitResource>
 *
 * @method PatientCircuitResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method PatientCircuitResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method PatientCircuitResource[]    findAll()
 * @method PatientCircuitResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientCircuitResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PatientCircuitResource::class);
    }

    public function add(PatientCircuitResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PatientCircuitResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PatientCircuitResource[] Returns an array of PatientCircuitResource objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PatientCircuitResource
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
