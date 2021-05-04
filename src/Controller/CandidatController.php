<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Profil;
use App\Form\ProfilType;
use App\Form\CandidatType;
use App\Repository\CandidatRepository;
use App\Repository\CompetenceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/candidat")
 */
class CandidatController extends AbstractController
{
    /**
     * @Route("/", name="candidat_index", methods={"GET"})
     */
    public function index(CandidatRepository $candidatRepository): Response
    {
        
        return $this->render('candidat/index.html.twig', [
            'candidats' => $candidatRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="candidat_new", methods={"GET","POST"})
     */
    public function new(Request $request,CompetenceRepository $competenceRepo): Response
    {
        $candidat = new Candidat();
        $form = $this->createForm(CandidatType::class, $candidat);
        $profil= new Profil();
        $formProfil = $this->createForm(ProfilType::class, $profil, array( 
            'candidat' => true
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
            
            $candidat->setProfil($profil);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($candidat);
            $entityManager->persist($profil);
            $entityManager->flush();

            return $this->redirectToRoute('candidat_index');
        }

        return $this->render('candidat/new.html.twig', [
            'candidat' => $candidat,
            'form' => $form->createView(),
            'formProfil' => $formProfil->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="candidat_show", methods={"GET"})
     */
    public function show(Candidat $candidat): Response
    {
        return $this->render('candidat/show.html.twig', [
            'candidat' => $candidat,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="candidat_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Candidat $candidat,CompetenceRepository $competenceRepo): Response
    {
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);
        $profil= $candidat->getProfil();
        $formProfil = $this->createForm(ProfilType::class, $profil, array( 
            'candidat' => true
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

            
            $candidat->setProfil($profil);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('candidat_index');
        }

        return $this->render('candidat/edit.html.twig', [
            'candidat' => $candidat,
            'form' => $form->createView(),
            'formProfil'=>$formProfil->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="candidat_delete", methods={"POST"})
     */
    public function delete(Request $request, Candidat $candidat): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidat->getId(), $request->request->get('_token'))) {
            $profil= $candidat->getProfil();
            $competences=$profil->getCompetence();
            foreach ($competences->toArray() as $competence){
              
                $profil->removeCompetence($competence);
                
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($profil);
            $entityManager->remove($candidat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('candidat_index');
    }
}
