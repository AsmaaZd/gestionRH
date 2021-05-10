<?php

namespace App\Repository;

use App\Entity\Recruteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Recruteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recruteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recruteur[]    findAll()
 * @method Recruteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecruteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recruteur::class);
    }

    // /**
    //  * @return Recruteur[] Returns an array of Recruteur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function searchForEntretien($candidatAnneesExp,$competenceArray){
        // return $this->createQueryBuilder('r')
        //     ->leftJoin('r.', 'u')
        //     ->where('u = :user')
        //     ->andWhere('r.profil.nbAnneesExp >= :val1')
        //     // ->andWhere('r.profil.competence in :val2')
        //     ->setParameter('val1', $candidatAnneesExp)
        //     // ->setParameter('val2', $competenceArray)
        //     ->setMaxResults(1)
        //     ->getQuery()
        //     ->getResult()
        // ;
        $qb = $this->createQueryBuilder('r');
        $qb
        ->select('r', 'p')
        ->leftJoin('r.profil', 'p')
        // ->leftJoin('p.competence','c')
        ->where('p.nbAnneesExp >= :nbAnneesExp')
        // ->andWhere('c.competence = :competenceArray')
        ->setParameter('nbAnneesExp', $candidatAnneesExp)
        // ->setParameter('competenceArray', "PHP")
        ->setMaxResults(1);

    return $qb->getQuery()->getResult();
    }

    public function searchForAnneesExp($nbAnneesExp){
        return $this->createQueryBuilder('r')
            ->leftJoin('r.profil','p')
            ->andWhere('p.nbAnneesExp >= :nbAnneesExp')
            ->setParameter('nbAnneesExp', $nbAnneesExp)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findRecruteurCompetenceOk($recruteursPlusExp,$competenceOne,$dateDispo){
        return $this->createQueryBuilder('r')
            ->where('r = :recruteur')
            ->setParameter('recruteur', $recruteursPlusExp)
            ->leftJoin('r.calendars','d')
            ->leftJoin('r.profil','p')
            ->leftJoin('p.competence','c')
            ->andWhere('d.start = :dateDispo ')
            ->andWhere('c.competence = :competenceOne')
            ->setParameter('dateDispo', $dateDispo)
            ->setParameter('competenceOne', $competenceOne)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findAllPossibleRecruteurs($OldDateEntretien){
        return $this->createQueryBuilder('r')
            ->leftJoin('r.calendars','d')
            ->andWhere('d.start = :dateDispo ')
            ->setParameter('dateDispo', $OldDateEntretien)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findRecruteurDateOkCompetenceOk($recruteurPlusExp, $competenceOne,$dateEntretien){
        return $this->createQueryBuilder('r')
            ->where('r = :recruteur')
            ->setParameter('recruteur', $recruteurPlusExp)
            ->leftJoin('r.calendars','d')
            ->leftJoin('r.profil','p')
            ->leftJoin('p.competence','c')
            ->andWhere('d.start = :dateDispo ')
            ->andWhere('c.competence = :competenceOne')
            ->setParameter('dateDispo', $dateEntretien)
            ->setParameter('competenceOne', $competenceOne)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllPossibleRecruteursDateAndExp($dateEntretien,$recruteursPlusExp){
        return $this->createQueryBuilder('r')
            ->where('r in :recruteur')
            ->setParameter('recruteur', $recruteursPlusExp)
            ->leftJoin('r.calendars','d')
            ->andWhere('d.start = :dateDispo ')
            ->setParameter('dateDispo', $dateEntretien)
            ->getQuery()
            ->getResult()
        ;
    }
    
    /*
    public function findOneBySomeField($value): ?Recruteur
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
