<?php

namespace App\Controller;

use DateTime;
use App\Entity\Calendar;
use App\Entity\Entretien;
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
     * @Route("/api/calendar/{id}/edit", name="api_calendar_event_edit", methods={"PUT"})
     */
    public function majEvent(?Calendar $calendar, Request $request, EntityManagerInterface $manager,EntretienRepository $entretienRepo,RecruteurRepository $recruteurRepository,CandidatRepository $candidatRepository): Response
    {

        // recuperer es donnees envoyer par FullCalendar
        $donnees= json_decode($request->getContent());
        // dd($donnees);

        if( 
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->isInterview) && $donnees->isInterview === 0
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

            //creer une nouvelle dispo 
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
            //supprimer l'ancien entretien
            $ancienEntretien= $entretienRepo->find($donnees->id);
            $manager->remove($ancienEntretien);
            // creer un nv entretien
            $entretien = new Entretien();
            $entretien->setCandidat($candidatRepository->find($donnees->candidat))
                    ->setDateEntretien(new Datetime($donnees->start))
                    ->setRecruteur($recruteurRepository->find($donnees->recruteur));
            $manager->persist($entretien);
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
    public function deleteEvent(Calendar $calendar, Request $request, EntityManagerInterface $manager): Response
    {

        // recuperer es donnees envoyer par FullCalendar
        $donnees= json_decode($request->getContent());

        if( 
            
            isset($donnees->start) && !empty($donnees->start)
        ){
            //mes donnees sont completes
            // initialise un code
            $code=200;
            $manager->remove($calendar);
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
}
