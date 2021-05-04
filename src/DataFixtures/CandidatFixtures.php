<?php

namespace App\DataFixtures;

// use Faker\Factory;
use Faker\Factory;
use App\Entity\Profil;
use App\Entity\Candidat;
use App\Entity\Disponibilite;
use App\Entity\Recruteur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CandidatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $faker= Factory::create('fr_FR');

        for($i=1;$i<=10;$i++){  
           
            $candidat=new Candidat();
            $candidat->setNom("nom$i")
                    ->setPrenom("prenom$i")
                    ;

            $profil=new Profil();
            $profil->setNbAnneesExp(mt_rand(0,10));
        
            $manager->persist($profil);
                    
            $candidat->setProfil($profil);

            $manager->persist($candidat);
        }

        
        $manager->flush();
    }
}
