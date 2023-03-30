<?php

namespace App\Repository;

use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Patient>
 *
 * @method Patient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Patient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Patient[]    findAll()
 * @method Patient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patient::class);
    }

    public function add(Patient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Patient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
//     * @return Patient[] Returns an array of Patient objects
//     */
    public function findAllPatient(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('LOWER(p.lastname)')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findMaxId(): int
    {
        return $this->createQueryBuilder('p')
            ->select('MAX(p.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function deleteALl()
    {
        $this->createQueryBuilder('p')
            ->delete()
            ->getQuery()
            ->execute()
        ;
    }

    public function getNumberOfPatients(): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function setFromArray(array $data, ManagerRegistry $registry)
    {
        $patient = new Patient();
        $patient->setFirstname($data['firstname']);
        $patient->setLastname($data['lastname']);
        $this->getEntityManager()->persist($patient);
        $this->getEntityManager()->flush();
        if ($patient->getId() == NULL) {
            $patient2 = new Patient();
            $patient2->setId($data['id']);
            $patient2->setFirstname($data['firstname']);
            $patient2->setLastname($data['lastname']);
            $this->getEntityManager()->persist($patient2);
            $this->getEntityManager()->flush();
            $patient = $patient2;
        }
        $this->changeId($patient->getId(), $data['id']);
    }

    public function changeId(int $oldId, int $newId)
    {
        $query = $this->createQueryBuilder('p')
            ->update()
            ->set('p.id', $newId)
            ->where('p.id = :oldId')
            ->setParameter('oldId', $oldId)
            ->getQuery()
            ->execute()
        ;
        //change sequence value
        $this->getEntityManager()->getConnection()->exec("UPDATE sqlite_sequence SET seq = $newId WHERE name = '" . 'patient' . "'");
    }

//    public function findOneBySomeField($value): ?Patient
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}