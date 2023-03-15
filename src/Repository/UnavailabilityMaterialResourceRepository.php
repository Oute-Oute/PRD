<?php

namespace App\Repository;

use App\Entity\UnavailabilityMaterialResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UnavailabilityMaterialResource>
 *
 * @method UnavailabilityMaterialResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method UnavailabilityMaterialResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method UnavailabilityMaterialResource[]    findAll()
 * @method UnavailabilityMaterialResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnavailabilityMaterialResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnavailabilityMaterialResource::class);
    }

    public function add(UnavailabilityMaterialResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UnavailabilityMaterialResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function deleteALl(): void
    {
        $this->getEntityManager()->createQuery('DELETE FROM App\Entity\UnavailabilityMaterialResource')->execute();
    }

    public function setFromArray(array $data, ManagerRegistry $registry)
    {
        $unavailabilityMR = new UnavailabilityMaterialResource();
        $materialResource = $registry->getRepository('App\Entity\MaterialResource')->findOneBy(['id' => $data['materialresource']['id']]);
        $unavailabilityMR->setMaterialresource($materialResource);
        $unavailability = $registry->getRepository('App\Entity\Unavailability')->findOneBy(['id' => $data['unavailability']['id']]);
        $unavailabilityMR->setUnavailability($unavailability);
        $this->add($unavailabilityMR, true);
        $this->changeId($unavailabilityMR->getId(), $data['id']);
    }

    public function changeId($oldId, $newId): void
    {
        $this->getEntityManager()->createQuery('UPDATE App\Entity\UnavailabilityMaterialResource u SET u.id = :newId WHERE u.id = :oldId')
            ->setParameter('newId', $newId)
            ->setParameter('oldId', $oldId)
            ->execute();
    }

//    /**
//     * @return UnavailabilityMaterialResource[] Returns an array of UnavailabilityMaterialResource objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UnavailabilityMaterialResource
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}