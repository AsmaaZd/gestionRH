<?php

namespace App\Repository;

use App\Entity\DispoSalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DispoSalle|null find($id, $lockMode = null, $lockVersion = null)
 * @method DispoSalle|null findOneBy(array $criteria, array $orderBy = null)
 * @method DispoSalle[]    findAll()
 * @method DispoSalle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DispoSalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DispoSalle::class);
    }

    // /**
    //  * @return DispoSalle[] Returns an array of DispoSalle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DispoSalle
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
