<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class CandidatControllerTest extends WebTestCase
{
    //WebTestCase tests en lien avec des request et response

    public function testcandidatList(){

        $client= static::createClient();
        $client->request('GET','/');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}