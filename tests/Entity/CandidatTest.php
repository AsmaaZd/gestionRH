<?php

namespace App\Tests\Entity;

use App\Entity\Candidat;
use Symfony\Component\Validator\Validation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CandidatTest extends KernelTestCase
{
    public function getEntity():Candidat{
        return (new Candidat() )
        ->setNom("ziadi")
        ->setPrenom("asmaa");
    }

    public function assertHasErrors(Candidat $candidat, int $number=0){
        self::bootKernel();
        // $validator = Validation::createValidator();
        // $validator = Validation::createValidatorBuilder();
        $validator = Validation::createValidator();

        
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping(true)
            ->addDefaultDoctrineAnnotationReader()
            ->getValidator();
        
        $error= $validator->validate($candidat);
        // $error= $validator->getValidator()->validate($candidat);
        $this->assertCount($number,$error);
    }

    public function testValidEntity(){

        
        $this->assertHasErrors($this->getEntity(),0);
        
    }

    public function testInvalidEntity(){

        
         $this->assertHasErrors($this->getEntity()->setNom(2),2);
     }
  
}