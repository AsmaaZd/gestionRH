<?php

namespace App\Controller;

use App\Entity\Recruteur;
use App\Entity\Profil;
use App\Form\ProfilType;
use App\Form\RecruteurType;
use App\Repository\RecruteurRepository;
use App\Repository\CompetenceRepository;
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
    public function index(RecruteurRepository $recruteurRepository): Response
    {
        return $this->render('recruteur/index.html.twig', [
            'recruteurs' => $recruteurRepository->findAll(),
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
}
