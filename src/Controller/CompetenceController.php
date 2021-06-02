<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Form\CompetenceType;
use App\Repository\CompetenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/competence")
 */
class CompetenceController extends AbstractController
{
    /**
     * @Route("/", name="competence_index", methods={"GET"})
     */
    public function index(CompetenceRepository $competenceRepository): Response
    {
        return $this->render('competence/index.html.twig', [
            'competences' => $competenceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="competence_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $competence = new Competence();
        $form = $this->createForm(CompetenceType::class, $competence);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request->request);
            if(!$request->request->get('hiddencolor') or $request->request->get('hiddencolor')=="rgba(66, 68, 90, 1)"){
                $color="#".substr(md5(rand()), 0, 6);
                $competence->setColor($color);
            }
            else{
                $competence->setColor($request->request->get('hiddencolor'));
            }
            
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($competence);
            $entityManager->flush();

            return $this->redirectToRoute('competence_index');
        }

        return $this->render('competence/new.html.twig', [
            'competence' => $competence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="competence_show", methods={"GET"})
     */
    public function show(Competence $competence): Response
    {
        return $this->render('competence/show.html.twig', [
            'competence' => $competence,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="competence_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Competence $competence): Response
    {
        $form = $this->createForm(CompetenceType::class, $competence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            if(!$request->request->get('hiddencolor') or $request->request->get('hiddencolor')=="rgba(66, 68, 90, 1)"){
                if($request->request->get('hiddencoloredit')){
                    $competence->setColor($request->request->get('hiddencoloredit'));
                }
                else{
                    $color="#".substr(md5(rand()), 0, 6);
                    $competence->setColor($color);
                }
                
            }
            else{
                $competence->setColor($request->request->get('hiddencolor'));
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('competence_index');
        }

        return $this->render('competence/edit.html.twig', [
            'competence' => $competence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="competence_delete", methods={"POST"})
     */
    public function delete(Request $request, Competence $competence): Response
    {
        if ($this->isCsrfTokenValid('delete'.$competence->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($competence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('competence_index');
    }
}
