<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Form\SalleType;
use App\Entity\Visioconference;
use App\Form\VisioconferenceType;
use App\Repository\DispoSalleRepository;
use App\Repository\SalleRepository;
use App\Repository\VisioconferenceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/salle")
 */
class SalleController extends AbstractController
{
    /**
     * @Route("/", name="salle_index", methods={"GET","POST"})
     */
    public function index(Request $request,SalleRepository $salleRepository,VisioconferenceRepository $visioconferenceRepository): Response
    {
        $visioconference = new Visioconference();
        $form = $this->createForm(VisioconferenceType::class, $visioconference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($visioconference);
            $entityManager->flush();

            return $this->redirectToRoute('salle_index');
        }
        return $this->render('salle/index.html.twig', [
            'salles' => $salleRepository->findAll(),
            'visioconferences' => $visioconferenceRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="salle_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $salle = new Salle();
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salle->setDisponible(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($salle);
            $entityManager->flush();

            return $this->redirectToRoute('salle_index');
        }

        return $this->render('salle/new.html.twig', [
            'salle' => $salle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="salle_show", methods={"GET"})
     */
    public function show(Salle $salle): Response
    {
        return $this->render('salle/show.html.twig', [
            'salle' => $salle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="salle_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Salle $salle): Response
    {
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('salle_index');
        }

        return $this->render('salle/edit.html.twig', [
            'salle' => $salle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="salle_delete", methods={"POST"})
     */
    public function delete(Request $request, Salle $salle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$salle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($salle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('salle_index');
    }

    /**
     * @Route("/calendar/{id}", name="salle_calendar", methods={"GET","POST"})
     */
    public function salleCalendar(Salle $salle,DispoSalleRepository $dispoSalleRepo): Response
    {

        $disposSalle=$dispoSalleRepo->findBy(array('salle'=>$salle));
        // dd($disposSalle);
        $dispos=[];
        foreach($disposSalle as $dispo){
            if($dispo->getIsOccupied() == true){

                $dispos[]=[
                    'id' => $dispo->getId(),
                    'start' => $dispo->getJour()->format('Y-m-d'),
                    'allDay' => 1,
                    'backgroundColor' => "#DE8971",
                    'borderColor' => "#DE8971",
                    'isOccupied' => $dispo->getIsOccupied(),
                ];
            }
            else{
                $dispos[]=[
                    'id' => $dispo->getId(),
                    'start' => $dispo->getJour()->format('Y-m-d'),
                    'allDay' => 1,
                    'backgroundColor' => "#3788D8",
                    'borderColor' => "#3788D8",
                    'isOccupied' => $dispo->getIsOccupied(),
                ];
            }
            
        }
        $data= json_encode($dispos);

        return $this->render('salle/_calendar.html.twig', [
            'salle' => $salle,
            'data'=>compact('data'),
        ]);
    }
}
