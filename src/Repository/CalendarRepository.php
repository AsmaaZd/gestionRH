<?php

namespace App\Repository;

use App\Entity\Calendar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Calendar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calendar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calendar[]    findAll()
 * @method Calendar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calendar::class);
    }

    // /**
    //  * @return Calendar[] Returns an array of Calendar objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    
    public function findCalendars($recruteur)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.recruteur = :recruteur')
            ->setParameter('recruteur', $recruteur)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findCalendar($recruteurDispo,$dateEntretien){
        return $this->createQueryBuilder('c')
            ->andWhere('c.recruteur = :recruteurDispo')
            ->andWhere('c.start = :dateEntretien')
            ->setParameter('recruteurDispo', $recruteurDispo)
            ->setParameter('dateEntretien', $dateEntretien)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findDispo($recruteur){
        return $this->createQueryBuilder('r')
            ->where('r.recruteur = :recruteur')
            ->setParameter('recruteur', $recruteur)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Calendar
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
