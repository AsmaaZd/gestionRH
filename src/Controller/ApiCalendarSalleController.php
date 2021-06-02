<?php

namespace App\Controller;

use App\Entity\DispoSalle;
use App\Repository\DispoSalleRepository;
use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiCalendarSalleController extends AbstractController
{
    /**
     * @Route("/api/calendar/salle", name="api_calendar_salle")
     */
    public function index(): Response
    {
        return $this->render('api_calendar_salle/index.html.twig', [
            'controller_name' => 'ApiCalendarSalleController',
        ]);
    }

    /**
     * @Route("/api/calendarDispo/new", name="api_calendar_salle_event_new", methods={"PUT"})
     */
    public function majEvent(Request $request, EntityManagerInterface $manager,SalleRepository $salleRepo,DispoSalleRepository $dispoSalle)
    {

        // recuperer es donnees envoyer par FullCalendar
        $donnees = json_decode($request->getContent());

        // dd($donnees);
        $salle=$salleRepo->find($donnees->salle);
        $dateDispo=new \Datetime($donnees->start);
        $dateAlreadyExist= $dispoSalle->findBy(array("salle"=>$salle,"jour"=>$dateDispo));
       
        if(!$dateAlreadyExist){
            $dispoSalle= new DispoSalle();

            $dispoSalle->setSalle($salle)
                        ->setIsOccupied($donnees->isOccupied)
                        ->setJour($dateDispo)
                        ->setDate($dateDispo);
            $manager->persist($dispoSalle);
            $manager->flush();
            return new Response('OK', 200);
        }

        else{
            return new Response('Date existe déjà');
        }

        
        
       
    }
}
