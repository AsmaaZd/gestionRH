<?php

namespace App\Controller;

use App\Entity\Recruteur;
use App\Form\RecruteurType;
use App\Repository\RecruteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function new(Request $request): Response
    {
        $recruteur = new Recruteur();
        $form = $this->createForm(RecruteurType::class, $recruteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recruteur);
            $entityManager->flush();

            return $this->redirectToRoute('recruteur_index');
        }

        return $this->render('recruteur/new.html.twig', [
            'recruteur' => $recruteur,
            'form' => $form->createView(),
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
    public function edit(Request $request, Recruteur $recruteur): Response
    {
        $form = $this->createForm(RecruteurType::class, $recruteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recruteur_index');
        }

        return $this->render('recruteur/edit.html.twig', [
            'recruteur' => $recruteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recruteur_delete", methods={"POST"})
     */
    public function delete(Request $request, Recruteur $recruteur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recruteur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recruteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('recruteur_index');
    }
}
