<?php

namespace App\Controller;

use App\Entity\Visioconference;
use App\Form\VisioconferenceType;
use App\Repository\VisioconferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/visioconference")
 */
class VisioconferenceController extends AbstractController
{
    /**
     * @Route("/", name="visioconference_index", methods={"GET"})
     */
    public function index(VisioconferenceRepository $visioconferenceRepository): Response
    {
        return $this->render('salle/index.html.twig', [
            'visioconferences' => $visioconferenceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="visioconference_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $visioconference = new Visioconference();
        $form = $this->createForm(VisioconferenceType::class, $visioconference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($visioconference);
            $entityManager->flush();

            return $this->redirectToRoute('visioconference_index');
        }

        return $this->render('visioconference/new.html.twig', [
            'visioconference' => $visioconference,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="visioconference_show", methods={"GET"})
     */
    public function show(Visioconference $visioconference): Response
    {
        return $this->render('visioconference/show.html.twig', [
            'visioconference' => $visioconference,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="visioconference_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Visioconference $visioconference): Response
    {
        $form = $this->createForm(VisioconferenceType::class, $visioconference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('visioconference_index');
        }

        return $this->render('visioconference/edit.html.twig', [
            'visioconference' => $visioconference,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="visioconference_delete", methods={"POST"})
     */
    public function delete(Request $request, Visioconference $visioconference): Response
    {
        if ($this->isCsrfTokenValid('delete'.$visioconference->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($visioconference);
            $entityManager->flush();
        }

        return $this->redirectToRoute('visioconference_index');
    }
}
