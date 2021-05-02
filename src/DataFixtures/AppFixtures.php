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

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');

        for($i=1;$i<=10;$i++){  
            $nom=$faker->firstName();
            $prenom=$faker->lastName();
            $candidat=new Candidat();
            $candidat->setNom($nom)
                    ->setPrenom($prenom)
                    ;

            $profil=new Profil();
            $profil->setNbAnneesExp(mt_rand(0,10));
        
            $manager->persist($profil);
                    
            $candidat->setProfil($profil);

            $manager->persist($candidat);
        }

        for($j=1;$j<=5;$j++){  
            $nom=$faker->firstName();
            $prenom=$faker->lastName();
            $recruteur=new Recruteur();
            $recruteur->setNom($nom)
                    ->setPrenom($prenom)
                    ;

            $profil=new Profil();
            $profil->setNbAnneesExp(mt_rand(5,15));

            for($l=1;$l<= mt_rand(2,6);$l++){
                $disponibilite= new Disponibilite();
                $disponibilite->setDateDispo($faker->dateTimeInInterval('now', '+3 month'));
                $disponibilite->setRecruteur($recruteur);
                $manager->persist($disponibilite);
            }
        
            $manager->persist($profil);
                    
            $recruteur->setProfil($profil);

            $manager->persist($recruteur);
        }
        $manager->flush();
    }
}
