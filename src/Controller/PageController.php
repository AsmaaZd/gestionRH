<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/", name="acceuil")
     */
    public function acceuil(): Response
    {
        return $this->render('page/acceuil.html.twig', [
            // 'controller_name' => 'PageController',
        ]);
    }
}
