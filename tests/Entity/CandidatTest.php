<?php

namespace App\Tests;

use App\Entity\Candidat;
use PHPUnit\Framework\TestCase;

class CandidatTest extends TestCase{

    public function testCandidat(){
        
        $candidat= new Candidat();

        $candidat->setNom("ziadi");

        $this->assertEquals("ziadi",$candidat->getNom());
    }
}