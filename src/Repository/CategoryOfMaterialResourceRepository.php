<?php

namespace App\Repository;

use App\Entity\CategoryOfMaterialResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryOfMaterialResource>
 *
 * @method CategoryOfMaterialResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryOfMaterialResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryOfMaterialResource[]    findAll()
 * @method CategoryOfMaterialResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryOfMaterialResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryOfMaterialResource::class);
    }

    public function add(CategoryOfMaterialResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CategoryOfMaterialResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function deleteALl()
    {
        $qb = $this->createQueryBuilder('c');
        $qb->delete();
        $query = $qb->getQuery()->getResult();
        return $query;
    }

    public function setFromArray(array $data, ManagerRegistry $registry)
    {
        $categoryOfMaterialResource = new CategoryOfMaterialResource();
        $materialResourceCategory = $registry->getRepository("App\Entity\MaterialResourceCategory")->find($data['materialresourcecategory']['id']);
        $categoryOfMaterialResource->setMaterialresourcecategory($materialResourceCategory);
        $materialResource = $registry->getRepository("App\Entity\MaterialResource")->find($data['materialresource']['id']);
        $categoryOfMaterialResource->setMaterialresource($materialResource);
        $this->add($categoryOfMaterialResource, true);
        if ($categoryOfMaterialResource->getId() == NULL) {
            $CMR = new $categoryOfMaterialResource();
            $CMR->setMaterialresource($materialResource);
            $CMR->setMaterialresourcecategory($materialResourceCategory);
            $CMR->setId($data['id']);
            $this->add($CMR, true);
            $categoryOfMaterialResource = $CMR;
        }
        $this->changeId($categoryOfMaterialResource->getId(), $data['id']);
    }

    public function changeId($oldId, $newId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->update()
            ->set('c.id', ':newId')
            ->where('c.id = :oldId')
            ->setParameter('newId', $newId)
            ->setParameter('oldId', $oldId);
        $query = $qb->getQuery()->getResult();

        $this->getEntityManager()->getConnection()->exec("UPDATE sqlite_sequence SET seq = $newId+1 WHERE name = '" . 'category_of_material_resource' . "'");
        return $query;
    }

//    /**
//     * @return CategoryOfMaterialResource[] Returns an array of CategoryOfMaterialResource objects
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

//    public function findOneBySomeField($value): ?CategoryOfMaterialResource
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}