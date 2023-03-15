<?php

namespace App\Repository;

use App\Entity\SimulationInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @extends ServiceEntityRepository<SimulationInfo>
 *
 * @method SimulationInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method SimulationInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method SimulationInfo[]    findAll()
 * @method SimulationInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SimulationInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SimulationInfo::class);
    }

    public function add(SimulationInfo $entity, bool $flush = false): int
    {
        $this->getEntityManager()->persist($entity);
        $lastId = new Integer;
        if ($flush) {
            $this->getEntityManager()->flush();
            $lastId = $entity->getId();
        }
        return $lastId;
    }

    public function remove(SimulationInfo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllOrderByCurrent(): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.iscurrent', 'DESC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return SimulationInfo[] Returns an array of SimulationInfo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SimulationInfo
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}