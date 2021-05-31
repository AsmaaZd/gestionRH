<?php

namespace App\Repository;

use App\Entity\Visioconference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Visioconference|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visioconference|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visioconference[]    findAll()
 * @method Visioconference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisioconferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visioconference::class);
    }

    // /**
    //  * @return Visioconference[] Returns an array of Visioconference objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Visioconference
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
