<?php

namespace App\Repository;

use App\Entity\MaterialResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MaterialResource>
 *
 * @method MaterialResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialResource[]    findAll()
 * @method MaterialResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterialResource::class);
    }

    public function add(MaterialResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MaterialResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return MaterialResourceCategory[] Returns an array of MaterialResourceCategory objects
     */
    public function findMaterialResourcesSorted(): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('LOWER(m.materialresourcename)')
            ->getQuery()
            ->getResult();
    }

    public function findMaxId(): int
    {
        return $this->createQueryBuilder('m')
            ->select('MAX(m.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function deleteALl()
    {
        $qb = $this->createQueryBuilder('m');
        $qb->delete();
        $qb->getQuery()->execute();
    }

    public function setFromArray(array $data, ManagerRegistry $registry)
    {
        $materialResource = new MaterialResource();
        $materialResource->setMaterialresourcename($data['materialresourcename']);
        $this->add($materialResource, true);
        $this->changeId($materialResource->getId(), $data['id']);
    }

    public function changeId(int $oldId, int $newId)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->update();
        $qb->set('m.id', $newId);
        $qb->where('m.id = :oldId');
        $qb->setParameter('oldId', $oldId);
        $qb->getQuery()->execute();
    }
//    /**
//     * @return MaterialResource[] Returns an array of MaterialResource objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MaterialResource
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}