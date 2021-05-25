<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Repository\CalendarRepository;
use App\Repository\CandidatRepository;
use App\Repository\EntretienRepository;
use App\Repository\RecruteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CalendarApiController extends AbstractController
{
    /**
     * @Route("/calendar/api", name="calendar_api")
     */
    public function index(): Response
    {
        return $this->render('calendar_api/index.html.twig', [
            'controller_name' => 'CalendarApiController',
        ]);
    }

    /**
     * @Route("/api/calendar/{id}/edit", name="api_calendar_event_edit", methods={"PUT"})
     */
    public function majEvent(?Calendar $calendar, Request $request, EntityManagerInterface $manager,EntretienRepository $entretienRepo,RecruteurRepository $recruteurRepository,CandidatRepository $candidatRepository,CalendarRepository $calendarRepo): Response
    {
        // recuperer es donnees envoyer par FullCalendar
        $donnees= json_decode($request->getContent());

        if(isset($donnees->start) && !empty($donnees->start)){
            if(isset($donnees->isInterview) && $donnees->isInterview === 0){

            
                $code=200;
                //verifier si l'id existe 
                if(!$calendar){
                    $calendar= new Calendar();
                    $code=201;
                }
                $calendar->setStart(new \Datetime($donnees->start))
                        ->setAllDay($donnees->allDay)
                        ->setEnd(new \Datetime($donnees->start));
               
                $manager->persist($calendar);
                $manager->flush();

                // return new Response('OK',$code);
                $response= new Response('OK',$code);
                $response->headers->set('Access-Control-Allow-Origin', '*');
                return $response;
            }
            elseif(isset($donnees->isInterview) && !empty($donnees->isInterview) && $donnees->isInterview === 1){
                $code=201;
                //1 changer la date de l'entretien
                $entretienWithNewDate = $entretienRepo->findBy(array('recruteur'=>$donnees->recruteur,'dateEntretien'=>new \Datetime($donnees->oldDate) ));
                if($entretienWithNewDate){
                    $entretienWithNewDate[0]->setDateEntretien(new \Datetime($donnees->start));
                    $manager->persist($entretienWithNewDate[0]);
                }

                //2 creer une nvl dispo avec l'ancienne date de l'entretien
                $calendar= new Calendar();
                $calendar->setRecruteur($recruteurRepository->find($donnees->recruteur))
                        ->setStart(new \Datetime($donnees->oldDate))
                        ->setAllDay($donnees->allDay)
                        ->setEnd(new \Datetime($donnees->start));
                $manager->persist($calendar);

                //3 supprimer la dispo remplacée par l'entretien
                $dispoReplaced = $calendarRepo->findBy(array('recruteur'=>$donnees->recruteur,'start'=>new \Datetime($donnees->start)));
                if($dispoReplaced){
                    $manager->remove($dispoReplaced[0]);
                }

                $manager->flush();

                $response= new Response('OK',$code);
                // $response->headers->set('Access-Control-Allow-Origin', '*');
                return $response;
            }
        }
        else{
            //mes donnees sont incompletes
            return new Response('Donnees incompletes',404);
        }
        return $this->render('api_calendar/index.html.twig', [
            'controller_name' => 'ApiCalendarController',
        ]);

    }
}
