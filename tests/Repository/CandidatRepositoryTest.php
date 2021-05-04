<?php

namespace App\Tests\Repository;



use App\DataFixtures\CandidatFixtures;
use App\Repository\CandidatRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CandidatRepositoryTest extends KernelTestCase
{

    use FixturesTrait;

    public function testCandidat()
    {
        self::bootKernel();
        // $this->loadFixtures([CandidatFixtures::class]);
        $loader = new \Nelmio\Alice\Loader\NativeLoader();
        $candidats = $loader->loadFile(__DIR__ . '/CandidatRepositoryTestFixtures.yaml');
        // $candidats = $this->loadFixtureFiles([
        //     __DIR__ . '/CandidatRepositoryTestFixtures.yaml'
        // ]);
        // $candidats['candidat1'];
        $candidats = self::$container->get(CandidatRepository::class)->count([]);
        $this->assertEquals(10, $candidats);
    }
}
