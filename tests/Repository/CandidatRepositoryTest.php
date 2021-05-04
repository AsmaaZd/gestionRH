<?php

namespace App\Tests\Repository;


use App\DataFixtures\CandidatFixtures;
use App\Repository\CandidatRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CandidatRepositoryTest extends KernelTestCase{

    use FixturesTrait;

    public function testCandidat(){
        self::bootKernel();
        $this->loadFixtures([CandidatFixtures::class]);
        $candidats= self::$container->get(CandidatRepository::class)->count([]);
        $this->assertEquals(10,$candidats);
    }
} 