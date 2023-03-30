<?php

namespace App\Repository;

use App\Entity\UnavailabilityHumanResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UnavailabilityHumanResource>
 *
 * @method UnavailabilityHumanResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method UnavailabilityHumanResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method UnavailabilityHumanResource[]    findAll()
 * @method UnavailabilityHumanResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnavailabilityHumanResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnavailabilityHumanResource::class);
    }

    public function add(UnavailabilityHumanResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UnavailabilityHumanResource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function deleteALl(): void
    {
        $this->getEntityManager()->createQuery('DELETE FROM App\Entity\UnavailabilityHumanResource')->execute();
    }

    public function setFromArray(array $data, ManagerRegistry $registry)
    {
        $unavailabilityHR = new UnavailabilityHumanResource();
        $humanResource = $registry->getRepository('App\Entity\HumanResource')->findOneBy(['id' => $data['humanresource']['id']]);
        $unavailabilityHR->setHumanresource($humanResource);
        $unavailability = $registry->getRepository('App\Entity\Unavailability')->findOneBy(['id' => $data['unavailability']['id']]);
        var_dump($unavailability);
        var_dump($data['unavailability']['id']);

        $unavailabilityHR->setUnavailability($unavailability);
        $this->add($unavailabilityHR, true);
        if ($unavailabilityHR->getId() == NULL) {
            $UHR = new UnavailabilityHumanResource();
            $UHR->setHumanresource($humanResource);
            $UHR->setUnavailability($unavailability);
            $UHR->setId($data['id']);
            $this->add($UHR, true);
            $unavailabilityHR = $UHR;
        }
        $this->changeId($unavailabilityHR->getId(), $data['id']);
    }

    public function changeId($oldId, $newId): void
    {
        $this->getEntityManager()->createQuery('UPDATE App\Entity\UnavailabilityHumanResource u SET u.id = :newId WHERE u.id = :oldId')
            ->setParameter('newId', $newId)
            ->setParameter('oldId', $oldId)
            ->execute();
        $this->getEntityManager()->getConnection()->exec("UPDATE sqlite_sequence SET seq = $newId WHERE name = '" . 'unavailability_human_resource' . "'");
    }

//    /**
//     * @return UnavailabilityHumanResource[] Returns an array of UnavailabilityHumanResource objects
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

//    public function findOneBySomeField($value): ?UnavailabilityHumanResource
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}