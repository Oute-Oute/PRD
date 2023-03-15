<?php

namespace App\Repository;

use App\Entity\HumanResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HumanResource>
 *
 * @method HumanResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method HumanResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method HumanResource[]    findAll()
 * @method HumanResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HumanResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HumanResource::class);
    }

    public function add(HumanResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HumanResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return HumanResource[] Returns an array of HumanResource objects
     */
    public function findAllHumanResources(): array
    {
        return $this->createQueryBuilder('h')
            ->orderBy('h.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return HumanResource[] Returns an array of HumanResource objects
     */
    public function findHumanResourcesSorted(): array
    {
        return $this->createQueryBuilder('h')
            ->orderBy('LOWER(h.humanresourcename)')
            ->getQuery()
            ->getResult();
    }
    public function findMaxId(): int
    {
        return $this->createQueryBuilder('h')
            ->select('MAX(h.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function deleteALl(): void
    {
        $this->createQueryBuilder('h')
            ->delete()
            ->getQuery()
            ->execute();
    }

    public function getNumberOfHumanResources(): int
    {
        return $this->createQueryBuilder('h')
            ->select('COUNT(h.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function setFromArray(array $data, ManagerRegistry $registry)
    {
        $humanResource = new HumanResource();
        $humanResource->setHumanresourcename($data['humanresourcename']);
        $this->add($humanResource, true);
        $this->changeId($humanResource->getId(), $data['id']);
    }

    public function changeId(int $oldId, int $newId): void
    {
        $this->createQueryBuilder('h')
            ->update()
            ->set('h.id', $newId)
            ->where('h.id = :oldId')
            ->setParameter('oldId', $oldId)
            ->getQuery()
            ->execute();
    }
//    public function findOneBySomeField($value): ?HumanResource
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}