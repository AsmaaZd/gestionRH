<?php

namespace App\Controller;

use DateTime;
use App\Entity\Calendar;
use App\Entity\Entretien;
use App\Repository\CalendarRepository;
use App\Repository\CandidatRepository;
use App\Repository\EntretienRepository;
use App\Repository\RecruteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use SebastianBergmann\Environment\Console;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ApiCalendarController extends AbstractController
{
    /**
     * @Route("/api/calendar", name="api_calendar")
     */
    public function index(): Response
    {
        return $this->render('api_calendar/index.html.twig', [
            'controller_name' => 'ApiCalendarController',
        ]);
    }

    /**
     * @Route("/api/calendaoor/{id}/edit", name="api_calendar_event_edit", methods={"PUT"})
     */
    public function majEvent(?Calendar $calendar, Request $request, EntityManagerInterface $manager,EntretienRepository $entretienRepo,RecruteurRepository $recruteurRepository,CandidatRepository $candidatRepository,CalendarRepository $calendarRepo): Response
    {

        // recuperer es donnees envoyer par FullCalendar
        $donnees= json_decode($request->getContent());
        // dd($donnees);
        $calendarAlreadyExixst= $calendarRepo->findBy(array("start"=>new Datetime($donnees->start),"recruteur"=>$donnees->recruteur));
            
        if( 
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->isInterview) && $donnees->isInterview === 0 &&
            !$calendarAlreadyExixst
        ){
            
            //mes donnees sont completes
            // initialise un code
            $code=200;
            //verifier si l'id existe 
            if(!$calendar){
                $calendar= new Calendar();
                $code=201;
            }
            $calendar->setStart(new Datetime($donnees->start));
            $calendar->setAllDay($donnees->allDay);
            if($donnees->allDay){
                $calendar->setEnd(new Datetime($donnees->start));
            }
            else{
                $calendar->setEnd(new Datetime($donnees->start));
            }

            $manager->persist($calendar);
            $manager->flush();

            return new Response('OK',$code);
        }
        elseif(
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->isInterview) && !empty($donnees->isInterview) && $donnees->isInterview === 1 &&
            isset($donnees->candidat) && !empty($donnees->candidat)
        ){
            $code=201;

            //1 changer la date de l'entretien
            $entretienWithNewDate = $entretienRepo->findBy(array('recruteur'=>$donnees->recruteur,'dateEntretien'=>new Datetime($donnees->oldDate) ));
            if($entretienWithNewDate){
                $entretienWithNewDate[0]->setDateEntretien(new Datetime($donnees->start));
                $manager->persist($entretienWithNewDate[0]);
            }

            //2 creer une nvl dispo avec l'ancienne date de l'entretien
            $calendar= new Calendar();
            $calendar->setRecruteur($recruteurRepository->find($donnees->recruteur))
                    ->setStart(new Datetime($donnees->oldDate))
                    ->setAllDay($donnees->allDay);
            if($donnees->allDay){
                $calendar->setEnd(new Datetime($donnees->start));
            }
            else{
                $calendar->setEnd(new Datetime($donnees->start));
            }
            $manager->persist($calendar);


            //3 supprimer la dispo remplacée par l'entretien
            $dispoReplaced = $calendarRepo->findBy(array('recruteur'=>$donnees->recruteur,'start'=>new Datetime($donnees->start)));
            if($dispoReplaced){
                $manager->remove($dispoReplaced);
            }

            $manager->flush();

            return new Response('OK',$code);
        }
        else{
            //mes donnees sont incompletes
            return new Response('Donnees incompletes',404);
        }
        return $this->render('api_calendar/index.html.twig', [
            'controller_name' => 'ApiCalendarController',
        ]);
    }


    /**
     * @Route("/api/calendar/{id}/delete", name="api_calendar_event_delete", methods={"DELETE"})
     */
    public function deleteEvent(?Calendar $calendar,?Entretien $entretien, Request $request, EntityManagerInterface $manager,RecruteurRepository $recruteurRepository): Response
    {

        // recuperer es donnees envoyer par FullCalendar
        $donnees= json_decode($request->getContent());

        if( 
            
            isset($donnees->start) && !empty($donnees->start) && $donnees->isInterview === 0
        ){
            
            //mes donnees sont completes
            // initialise un code
            $code=200;
            $manager->remove($calendar);
            $manager->flush();

            $response= new Response('OK',$code);
                $response->headers->set('Access-Control-Allow-Origin', '*');
                return $response;
        }
        elseif(
            isset($donnees->start) && !empty($donnees->start) &&
            $donnees->isInterview === 1 &&
            isset($donnees->oldDate) && !empty($donnees->oldDate) &&
            isset($donnees->recruteur) && !empty($donnees->recruteur)
        ){
            $code=200;
            $calendarWithOldDate = new Calendar();
            $calendarWithOldDate->setStart(new Datetime($donnees->oldDate))
                                ->setRecruteur($recruteurRepository->find($donnees->recruteur))
                                ->setEnd(new Datetime($donnees->oldDate))
                                ->setAllDay($donnees->allDay);
            $manager->persist($calendarWithOldDate);
            $manager->remove($entretien);
            $manager->flush();
            $response= new Response('OK',$code);
                $response->headers->set('Access-Control-Allow-Origin', '*');
                return $response;
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
