<?php

namespace App\Controller;

use DateTime;
use App\Entity\Candidat;
use App\Entity\Entretien;
use App\Entity\Recruteur;
use App\Form\EntretienType;
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
    public function edit(Request $request, Entretien $entretien, RecruteurRepository $recruteurRepo): Response
    {
        $candidat=$entretien->getCandidat();

        

            
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
            $dateEntretien=$entretien->getDateEntretien()->format('Y-m-d');
            
            $recruteursList=[];
            foreach ($recruteursPlusExp as $recruteurPlusExp) {

                foreach ($competenceArray as $competenceOne) {
                    $recruteurCompetenceOk = $recruteurRepo->findRecruteurCompetenceOk($recruteurPlusExp, $competenceOne,$dateEntretien);
                   
                    array_push($recruteursList,$recruteurCompetenceOk);
                    if (!$recruteurCompetenceOk) {
                        
                        break;
                        
                    }
                }
                // if ($recruteurCompetenceOk) {
                //     $recruteurDispo=$recruteurCompetenceOk[0];
                //     break;
                // }
            }
            // dd($recruteursList);
            // if($recruteurDispo){
            //     $entretien->setRecruteur($recruteurDispo);


            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($entretien);
            // $entityManager->flush();
            // $this->addFlash("NewEntretien" , "Entretien ajouté");
            // return $this->redirectToRoute('entretien_index');
            // }
            // else{
            //     $this->addFlash("pasDeDisponibilite" , "Aucun recruteur disponible!");
            //     return $this->redirectToRoute('candidat_index');
            // }
            
        












        $form = $this->createForm(EntretienType::class, $entretien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('entretien_index');
        }

        return $this->render('entretien/edit.html.twig', [
            'entretien' => $entretien,
            'form' => $form->createView(),
            'recruteursList' => $recruteursList,
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
}
