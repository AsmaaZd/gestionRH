<?php

namespace App\Repository;

use App\Entity\Salle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Salle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Salle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Salle[]    findAll()
 * @method Salle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salle::class);
    }

    // /**
    //  * @return Salle[] Returns an array of Salle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findSaleForEntretien($capacityMin,$dispo,$dateEntretien){
        return $this->createQueryBuilder('s')
            ->andWhere('s.capacity >= :capacityMin')
            ->setParameter('capacityMin', $capacityMin)
            ->andWhere('s.disponible = :dispo')
            ->setParameter('dispo', $dispo)
            ->leftJoin('s.dispoSalles','c')
            ->andWhere('c.jour = :dateEntretien ')
            ->setParameter('dateEntretien', $dateEntretien)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    /*
    public function findOneBySomeField($value): ?Salle
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
