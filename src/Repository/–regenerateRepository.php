<?php

namespace App\Repository;

use App\Entity\–regenerate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<–regenerate>
 *
 * @method –regenerate|null find($id, $lockMode = null, $lockVersion = null)
 * @method –regenerate|null findOneBy(array $criteria, array $orderBy = null)
 * @method –regenerate[]    findAll()
 * @method –regenerate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class –regenerateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, –regenerate::class);
    }

    public function save(–regenerate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(–regenerate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return –regenerate[] Returns an array of –regenerate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('�')
//            ->andWhere('�.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('�.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?–regenerate
//    {
//        return $this->createQueryBuilder('�')
//            ->andWhere('�.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
