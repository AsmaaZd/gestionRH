<?php

namespace App\Controller;

use App\Entity\Calendar;
use DateTime;
use App\Entity\Candidat;
use App\Entity\Entretien;
use App\Entity\Recruteur;
use App\Form\EntretienType;
use App\Repository\CalendarRepository;
use App\Repository\EntretienRepository;
use App\Repository\RecruteurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class EntretienController extends AbstractController
{
    /**
     * @Route("/entretien", name="entretien_index", methods={"GET"})
     */
    public function index(EntretienRepository $entretienRepository): Response
    {
        // echo phpinfo();
        // exit;
        return $this->render('entretien/index.html.twig', [
            'entretiens' => $entretienRepository->findAll(),
        ]);
    }

    /**
     * @Route("/entretien/new/{id}", name="entretien_new", methods={"GET","POST"})
     */
    public function new(Candidat $candidat, Request $request, RecruteurRepository $recruteurRepo): Response
    {
        $entretien = new Entretien();
        $entretien->setCandidat($candidat);
        $form = $this->createForm(EntretienType::class, $entretien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidatAnneesExp = $candidat->getProfil()->getNbAnneesExp();
            $candidatCompetences = $candidat->getProfil()->getCompetence()->toArray();
            $competenceArray = [];
            $recruteurDispo=null;
            foreach ($candidatCompetences as $competence) {
                $comp = $competence->getCompetence();
                array_push($competenceArray, $comp);
            }
            // Recuperer les recruteurs qui ont plus d'annees d'exp
            $recruteursPlusExp = $recruteurRepo->searchForAnneesExp($candidatAnneesExp);

            //Parmis ces recruteurs, recupere ceux qui ont plus de competences que candidats
            //date dispo 
            $dateEntretien=$request->request->get("entretien")["dateEntretien"];

            foreach ($recruteursPlusExp as $recruteurPlusExp) {

                foreach ($competenceArray as $competenceOne) {
                    $recruteurCompetenceOk = $recruteurRepo->findRecruteurCompetenceOk($recruteurPlusExp, $competenceOne,$dateEntretien);
                    if (!$recruteurCompetenceOk) {
                        
                        break;
                        
                    }
                }
                if ($recruteurCompetenceOk) {
                    $recruteurDispo=$recruteurCompetenceOk[0];
                    break;
                }
            }
            // dd($recruteurDispo);
            if($recruteurDispo){
                $entretien->setRecruteur($recruteurDispo);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entretien);
            $entityManager->flush();
            $this->addFlash("NewEntretien" , "Entretien ajouté");
            return $this->redirectToRoute('entretien_index');
            }
            else{
                $this->addFlash("pasDeDisponibilite" , "Aucun recruteur disponible!");
                return $this->redirectToRoute('candidat_index');
            }
            
        }

        return $this->render('entretien/new.html.twig', [
            'entretien' => $entretien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/entretien/{id}", name="entretien_show", methods={"GET"})
     */
    public function show(Entretien $entretien): Response
    {
        return $this->render('entretien/show.html.twig', [
            'entretien' => $entretien,
        ]);
    }

    /**
     * @Route("/entretien/{id}/edit", name="entretien_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Entretien $entretien, CalendarRepository $calendarRepo, RecruteurRepository $recruteurRepo): Response
    {
        //edit date
        $recruteur= $entretien->getRecruteur();
        $datesDispo=$calendarRepo->findDispo($recruteur);
        
        //edit recruteur
        $recruteurs=[];
        $candidatAnneesExp = $entretien->getCandidat()->getProfil()->getNbAnneesExp();
            $candidatCompetences = $entretien->getCandidat()->getProfil()->getCompetence()->toArray();
            $competenceArray = [];
            $recruteurDispo=[];
            foreach ($candidatCompetences as $competence) {
                $comp = $competence->getCompetence();
                array_push($competenceArray, $comp);
            }
            // Recuperer les recruteurs qui ont plus d'annees d'exp
            $recruteursPlusExp = $recruteurRepo->searchForAnneesExp($candidatAnneesExp);

            //Parmis ces recruteurs, recupere ceux qui ont plus de competences que candidats
            //date dispo 
            $dateEntretien=$entretien->getDateEntretien();
            // $rec=$recruteurRepo->findAllPossibleRecruteursDateAndExp($dateEntretien,$recruteursPlusExp);
            // dd($rec);
            foreach ($recruteursPlusExp as $recruteurPlusExp) {

                foreach ($competenceArray as $competenceOne) {
                    $recruteurCompetenceOk = $recruteurRepo->findRecruteurCompetenceOk($recruteurPlusExp, $competenceOne,$dateEntretien);
                    
                    if (!$recruteurCompetenceOk) {
                        // $recruteurs=[];
                        break;
                        
                    }
                    
                }
                if($recruteurCompetenceOk){
                    $recruteurs[]=$recruteurCompetenceOk;
                }
                
                
            }

            if($request->request->get("newDate") or $request->request->get("newRecruteur") ){
                $oldEntretienDate= $entretien->getDateEntretien();
                    $newCalendar= new Calendar();
                    $newCalendar->setStart($oldEntretienDate)
                                ->setRecruteur($entretien->getRecruteur())
                                ->setAllDay(1);

                if($request->request->get("newDate")){
                    $getDate= $request->request->get("newDate");
                    $newDate = \DateTime::createFromFormat('Y-m-d',$getDate);
                    $entretien->setDateEntretien($newDate );
                }
                elseif($request->request->get("newRecruteur")){
                    $entretien->setRecruteur($recruteurRepo->find($request->request->get("newRecruteur")));
                }

                
                
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($newCalendar);
                $entityManager->persist($entretien);
                // $dispoToRemove=$calendarRepo->findalendar($entretien->getRecruteur(),$entretien->getDateEntretien());
                $dispoToRemove=$calendarRepo->findBy(
                    ['recruteur' => $entretien->getRecruteur(), 'start' =>$entretien->getDateEntretien()]
                );
              
                $entityManager->remove($dispoToRemove[0]);
                $entityManager->flush();
                $this->addFlash("EditEntretien" , "Entretien modifié");
                return $this->redirectToRoute('entretien_index');
            }
            
            // dd($recruteurs);
            //     if ($recruteurCompetenceOk) {
                    
            //         $recruteurDispo[]=$recruteurCompetenceOk;
                  
            //     }
            // dd($recruteurDispo);



        return $this->render('entretien/edit.html.twig', [
            'entretien' => $entretien,
            'datesDispo' => $datesDispo,
            'recruteurs' => $recruteurs,
            // 'form' => $form->createView(),
            // 'recruteursList' => $recruteursList,
        ]);
    }

    /**
     * @Route("/entretien/{id}", name="entretien_delete", methods={"POST"})
     */
    public function delete(Request $request, Entretien $entretien): Response
    {
        if ($this->isCsrfTokenValid('delete' . $entretien->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($entretien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('entretien_index');
    }


    /**
     * @Route("/entretien/calendar/new/{id}", name="entretien_calendar_new", methods={"GET","POST"})
     */
    public function newEntretien(Candidat $candidat, Request $request, RecruteurRepository $recruteurRepo,CalendarRepository $calendarRepo): Response
    {
        $entretien = new Entretien();
        $entretien->setCandidat($candidat);
        $form = $this->createForm(EntretienType::class, $entretien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidatAnneesExp = $candidat->getProfil()->getNbAnneesExp();
            $candidatCompetences = $candidat->getProfil()->getCompetence()->toArray();
            $competenceArray = [];
            $recruteurDispo=null;
            foreach ($candidatCompetences as $competence) {
                $comp = $competence->getCompetence();
                array_push($competenceArray, $comp);
            }
            // Recuperer les recruteurs qui ont plus d'annees d'exp
            $recruteursPlusExp = $recruteurRepo->searchForAnneesExp($candidatAnneesExp);

            //Parmis ces recruteurs, recupere ceux qui ont plus de competences que candidats
            //date dispo 
            $dateEntretien=$request->request->get("entretien")["dateEntretien"];

            foreach ($recruteursPlusExp as $recruteurPlusExp) {

                foreach ($competenceArray as $competenceOne) {
                    $recruteurCompetenceOk = $recruteurRepo->findRecruteurDateOkCompetenceOk($recruteurPlusExp, $competenceOne,$dateEntretien);
                    // dd($recruteurCompetenceOk);
                    if (!$recruteurCompetenceOk) {
                        
                        break;
                        
                    }
                }
                if ($recruteurCompetenceOk) {
                    $recruteurDispo=$recruteurCompetenceOk[0];
                    break;
                }
            }
            // dd($recruteurDispo);
            if($recruteurDispo){
                $entretien->setRecruteur($recruteurDispo);
                $dispoToRemove=$calendarRepo->findCalendar($recruteurDispo,$dateEntretien);
                
               
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entretien);
            $entityManager->remove($dispoToRemove);
            $entityManager->flush();

            $this->addFlash("NewEntretien" , "Entretien ajouté");
            return $this->redirectToRoute('entretien_index');
            }
            else{
                $this->addFlash("pasDeDisponibilite" , "Aucun recruteur disponible!");
                return $this->redirectToRoute('candidat_index');
            }
            
        }

        return $this->render('entretien/new.html.twig', [
            'entretien' => $entretien,
            'form' => $form->createView(),
        ]);
    }
}
