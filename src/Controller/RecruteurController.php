<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\Calendar;
use App\Form\ProfilType;
use App\Entity\Recruteur;
use App\Form\CalendarType;
use App\Form\RecruteurType;
use App\Entity\Disponibilite;
use App\Form\DisponibiliteType;
use App\Repository\CalendarRepository;
use App\Repository\RecruteurRepository;
use App\Repository\CompetenceRepository;
use App\Repository\EntretienRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/recruteur")
 */
class RecruteurController extends AbstractController
{
    /**
     * @Route("/", name="recruteur_index", methods={"GET"})
     */
    public function index(RecruteurRepository $recruteurRepository,CalendarRepository $calendarrepo): Response
    {
        $recruteurs=$recruteurRepository->findAll();
        return $this->render('recruteur/index.html.twig', [
            'recruteurs' => $recruteurs,
            // 'allData'=>$allData
        ]);
    }

    /**
     * @Route("/new", name="recruteur_new", methods={"GET","POST"})
     */
    public function new(Request $request,CompetenceRepository $competenceRepo): Response
    {
        $recruteur = new Recruteur();
        $form = $this->createForm(RecruteurType::class, $recruteur);
        $profil= new Profil();
        $formProfil = $this->createForm(ProfilType::class, $profil, array( 
            'recruteur' => true
           ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {

            $nbAnneesExp=$request->request->get("profil")["nbAnneesExp"];
            $competences=$request->request->get("profil")["competence"];
            // dd($competences);
            
            $profil->setNbAnneesExp($nbAnneesExp);
            for($i=0;$i<count($competences);$i++){
                $competence=$competenceRepo->find($competences[$i]);
                $profil->addCompetence($competence);
            }
            
            $recruteur->setProfil($profil);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recruteur);
            $entityManager->persist($profil);
            $entityManager->flush();

            return $this->redirectToRoute('recruteur_index');
        }

        return $this->render('recruteur/new.html.twig', [
            'recruteur' => $recruteur,
            'form' => $form->createView(),
            'formProfil' => $formProfil->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="recruteur_show", methods={"GET"})
     */
    public function show(Recruteur $recruteur): Response
    {
        return $this->render('recruteur/show.html.twig', [
            'recruteur' => $recruteur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="recruteur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Recruteur $recruteur,CompetenceRepository $competenceRepo): Response
    {
        $form = $this->createForm(RecruteurType::class, $recruteur);
        $form->handleRequest($request);
        $profil= $recruteur->getProfil();
        $formProfil = $this->createForm(ProfilType::class, $profil, array( 
            'recruteur' => true
           ));

        if ($form->isSubmitted() && $form->isValid()) {
            $nbAnneesExp=$request->request->get("profil")["nbAnneesExp"];
            $competences=$request->request->get("profil")["competence"];
            // dd($competences);
            
            $profil->setNbAnneesExp($nbAnneesExp);
            
            $oldCompetences=$profil->getCompetence();

            foreach ($oldCompetences->toArray() as $oldCompetence){
                if(! in_array($oldCompetence,$competences)){
                    $profil->removeCompetence($oldCompetence);
                }
            }
            for($j=0;$j<count($competences);$j++){
                $competence=$competenceRepo->find($competences[$j]);
                $profil->addCompetence($competence);
            }

            
            $recruteur->setProfil($profil);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recruteur_index');
        }

        return $this->render('recruteur/edit.html.twig', [
            'recruteur' => $recruteur,
            'form' => $form->createView(),
            'formProfil'=>$formProfil->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="recruteur_delete", methods={"POST"})
     */
    public function delete(Request $request, Recruteur $recruteur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recruteur->getId(), $request->request->get('_token'))) {
            $profil= $recruteur->getProfil();
            $competences=$profil->getCompetence();
            foreach ($competences->toArray() as $competence){
              
                $profil->removeCompetence($competence);
                
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($profil);
            $entityManager->remove($recruteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('recruteur_index');
    }

    // /**
    //  * @Route("/addDisponibilite/{id}", name="recruteur_dispo_new")
    //  */
    // public function newDispo(CalendarRepository $calendarrepo,Recruteur $recruteur,Request $request,CompetenceRepository $competenceRepo): Response
    // {
    //     $events=$calendarrepo->findCalendars($recruteur);
    //     // dd($events);
    //     $rdvs=[];
    //     foreach($events as $event){
    //         $rdvs[]=[
    //             'id' => $event->getId(),
    //             'title' => $event->getTitle(),
    //             'start' => $event->getStart()->format('Y-m-d H:i:s'),
    //             'end' => $event->getEnd()->format('Y-m-d H:i:s'),
    //             'description' => $event->getDescription(),
    //             'allDay' => $event->getAllDay(),
    //             'backgroundColor' => $event->getBackgroundColor(),
    //             'borderColor' => $event->getBorderColor(),
    //             'textColor' => $event->getTextColor()
    //         ];
    //     }
    //     $data= json_encode($rdvs);
    //     $disponibilite= new Disponibilite();
    //     $form = $this->createForm(DisponibiliteType::class, $disponibilite);
        
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid() ) {

    //         $recruteur->addDisponibilite($disponibilite);
            
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($disponibilite);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('recruteur_index');
    //     }
        

    //     // return $this->render('recruteur/newDispo.html.twig', [
    //     //     'recruteur' => $recruteur,
    //     //     'form' => $form->createView(),
    //     //     'data' => compact('data'),
           
    //     // ]);

    //     return $this->render('recruteur/newDispo.html.twig',compact('data'));
        
    // }

     /**
     * @Route("/addDisponibilite/{id}", name="recruteur_dispo_new")
     */
    public function newDispo(CalendarRepository $calendarrepo,Recruteur $recruteur,Request $request,CompetenceRepository $competenceRepo,EntretienRepository $entretienrepo): Response
    {
        $events=$calendarrepo->findCalendars($recruteur);
        $rdvs=$entretienrepo->findBy(array('recruteur' => $recruteur));
        // dd($rdvs);

        // dd($events);
        $dispos=[];
        foreach($events as $event){
            $dispos[]=[
                'id' => $event->getId(),
                // 'title' => $event->getTitle(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                
                // 'description' => $event->getDescription(),
                'allDay' => $event->getAllDay(),
                // 'backgroundColor' => "#34656d",
                // 'borderColor' => $event->getBorderColor(),
                // 'textColor' => $event->getTextColor(),
                'recruteur' => $recruteur->getId(),
                'isInterview' => 0,
            ];
            if($event->getEnd()){
                $dispos[]=['end' => $event->getEnd()->format('Y-m-d H:i:s'),];
            }
        }

        foreach($rdvs as $rdv){
            $dispos[]=[
                'id' => $rdv->getId(),
                // 'title' => $event->getTitle(),
                'start' => $rdv->getDateEntretien()->format('Y-m-d'),
                
                // 'description' => $event->getDescription(),
                'allDay' => 1,
                'backgroundColor' => "#de8971",
                'borderColor' => "#de8971",
                // 'textColor' => $event->getTextColor(),
                'recruteur' => $recruteur->getId(),
                'isInterview' => 1,
                'candidat' => $rdv->getCandidat()->getId(),
            ];
        }
        $data= json_encode($dispos);
        
        $calendar = new Calendar();
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $getStart= $request->request->get("date-start");
            $getEnd= $request->request->get("date-end");
            $dateStart = \DateTime::createFromFormat('d/m/Y',$getStart);
            
            $calendar->setStart($dateStart);
            if($getEnd){
                $dateEnd = \DateTime::createFromFormat('d/m/Y',$getEnd);
                $calendar->setEnd($dateEnd);
            }
            
            // dd($calendar);
            $calendar->setRecruteur($recruteur);
            // dd($calendar);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($calendar);
            $entityManager->flush();

            return $this->redirectToRoute('recruteur_dispo_new', array(
                'id' => $recruteur->getId())
            );
        }

        return $this->render('recruteur/newDispo.html.twig',[
            'data'=>compact('data'),
            'form' =>$form->createView(),
            ]);
        
    }
}
