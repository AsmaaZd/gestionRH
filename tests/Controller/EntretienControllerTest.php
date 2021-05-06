<?php

namespace App\Tests\Controller;

use App\Entity\Profil;
use App\Entity\Candidat;
use App\Entity\Entretien;
use App\Entity\Competence;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class EntretienControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/entretien');

        $this->assertResponseIsSuccessful();
        // $this->assertSelectorTextContains('h1', 'Liste des entretiens');
        $this->assertCount(1, $crawler->filter('.table'));
    }

    public function testAssignRecruteurToCandidat(){

        $candidat=(new Candidat())
                ->setNom("ziadi")
                ->setPrenom("asmaa")
                ->setProfil(
                    (new Profil())->setNbAnneesExp(2)
                        ->addCompetence((new Competence())->setCompetence("PHP"))
                );
                
        $entretien = new Entretien();
        $entretien->setCandidat($candidat);

        $client = static::createClient();
        $crawler = $client->request('GET', '/entretien/new/1');
        // $this->assertSelectorTextContains('h1', 'Nouveau entretien');
        // $this->assertCount(1, $crawler->filter('input[type=submit]'));

        $buttonCrawlerNode = $crawler->filter('input[type=submit]');
        $form = $buttonCrawlerNode->form();
        
        $csrfToken= $client->getContainer()->get('security.csrf.token_manager')->getToken('delete-item');
        $form = $buttonCrawlerNode->form([
            'entretien[dateEntretien]'    => '15-05-2021',
            'entretien[_token]' => $csrfToken,
        ]);
        $client->submit($form);
        // $submittedToken = $request->request->get('token');
        // $this->isCsrfTokenValid('delete-item', $submittedToken);

        // $this->assertEquals("15-05-2021",$entretien->getDateEntretien()->format('d-m-Y'));
        // $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertResponseRedirects();
    }
}
