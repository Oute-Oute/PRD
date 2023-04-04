<?php

namespace App\Repository;

use App\Entity\CategoryOfHumanResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryOfHumanResource>
 *
 * @method CategoryOfHumanResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryOfHumanResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryOfHumanResource[]    findAll()
 * @method CategoryOfHumanResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryOfHumanResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryOfHumanResource::class);
    }

    public function add(CategoryOfHumanResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CategoryOfHumanResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return HumanResource[] Returns an array of HumanResource objects
     */
    public function findHumanResourceByCategory($idCategory)
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.humanresource', 'humanresource')
            ->select('humanresource.humanresourcename')
            ->orderBy('humanresource.humanresourcename', 'ASC')
            ->where('c.humanresourcecategory= :idCategory')
            ->setParameter('idCategory', $idCategory);
        $query = $qb->getQuery()->getResult();
        return $query;
    }

    public function deleteALl()
    {
        $qb = $this->createQueryBuilder('c');
        $qb->delete();
        $query = $qb->getQuery();
        $query->execute();
    }

    public function setFromArray(array $data, ManagerRegistry $registry)
    {
        $categoryOfHumanResource = new CategoryOfHumanResource();
        $humanResourceCategory = $registry->getRepository('App\Entity\HumanResourceCategory')->find($data['humanresourcecategory']['id']);
        $categoryOfHumanResource->setHumanresourcecategory($humanResourceCategory);
        $humanResource = $registry->getRepository('App\Entity\HumanResource')->find($data['humanresource']['id']);
        $categoryOfHumanResource->setHumanresource($humanResource);
        $this->add($categoryOfHumanResource, true);
        if ($categoryOfHumanResource->getId() == null) {
            $CHR = new $categoryOfHumanResource();
            $CHR->setHumanresource($humanResource);
            $CHR->setHumanresourcecategory($humanResourceCategory);
            $CHR->setId($data['id']);
            $this->add($CHR, true);
            $categoryOfHumanResource = $CHR;
        }
        $this->changeId($categoryOfHumanResource->getId(), $data['id']);
    }

    public function changeId($oldId, $newId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->update();
        $qb->set('c.id', $newId);
        $qb->where('c.id = :oldId');
        $qb->setParameter('oldId', $oldId);
        $query = $qb->getQuery();
        $query->execute();

        $this->getEntityManager()->getConnection()->exec("UPDATE sqlite_sequence SET seq = $newId+1 WHERE name = '" . 'category_of_human_resource' . "'");
    }

//    /**
//     * @return CategoryOfHumanResource[] Returns an array of CategoryOfHumanResource objects
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

//    public function findOneBySomeField($value): ?CategoryOfHumanResource
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}