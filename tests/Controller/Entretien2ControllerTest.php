<?php

namespace App\Tests\Controller;

use App\Entity\Candidat;
use App\Entity\Competence;
use App\Entity\Entretien;
use App\Entity\Profil;
use PHPUnit\Framework\TestCase;

class Entretien2ControllerTest extends TestCase
{

    public function testCandidatFoundRecruteur(){

        
        $candidat=(new Candidat())
                ->setNom("ziadi")
                ->setPrenom("asmaa")
                ->setProfil(
                    (new Profil())->setNbAnneesExp(2)
                        ->addCompetence((new Competence())->setCompetence("PHP"))
                );

        $date = \DateTime::createFromFormat('d-m-Y', '15-05-2021');
        $entretien=(new Entretien())->setDateEntretien($date)->setCandidat($candidat);

        $this->assertSame("15-05-2021",$entretien->getDateEntretien()->format('d-m-Y'));
    }
}