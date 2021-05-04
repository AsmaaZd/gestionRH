<?php

namespace App\Tests\Entity;

use App\Entity\Candidat;
use PHPUnit\Framework\TestCase;

class CandidatCodeTest extends TestCase{

    public function testCandidat(){
        
        $candidat= new Candidat();

        $candidat->setNom("ziadi");

        $this->assertEquals("ziadi",$candidat->getNom());
    }
}