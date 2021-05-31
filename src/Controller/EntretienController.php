<?php

namespace App\Controller;

use DateTime;
use Swift_Mailer;
use App\Entity\Calendar;
use App\Entity\Candidat;
use App\Entity\Entretien;
use App\Entity\Recruteur;
use App\Form\EntretienType;
use App\Entity\Visioconference;
use Symfony\Component\Mime\Email;
use App\Repository\SalleRepository;
use App\Repository\CalendarRepository;
use App\Repository\EntretienRepository;
use App\Repository\RecruteurRepository;
use App\Repository\VisioconferenceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
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

    public function changeEntretienEmail($entretien, \Swift_Mailer $mailer, $templateCandidat, $templateRecruteur)
    {
        $messageToCandidat = (new \Swift_Message('Hello Email'))
            ->setFrom('recrutementrh@gmail.com')
            ->setTo([
                $entretien->getCandidat()->getEmail() => $entretien->getCandidat()->getNom() . " " . $entretien->getCandidat()->getPrenom()
            ])
            ->setSubject('Nouveau entretien')
            ->setBody(
                $this->renderView(
                    // 'emails/nvEntretienCandidat.html.twig',
                    $templateCandidat,
                    [
                        'candidat' => $entretien->getCandidat(),
                        'recruteur' => $entretien->getRecruteur(),
                        'entretien' => $entretien,
                    ]
                ),
                'text/html'
            )

            ->addPart(
                $this->renderView(
                    'emails/nvEntretienCandidat.txt.twig'
                ),
                'text/plain'
            );
        $messageToRecruteur = (new \Swift_Message('Hello Email'))
            ->setFrom('recrutementrh@gmail.com')
            ->setTo([
                $entretien->getRecruteur()->getEmail() => $entretien->getRecruteur()->getNom() . " " . $entretien->getRecruteur()->getPrenom()
            ])
            ->setSubject('Nouveau entretien')
            ->setBody(
                $this->renderView(
                    $templateRecruteur,
                    [
                        'candidat' => $entretien->getCandidat(),
                        'recruteur' => $entretien->getRecruteur(),
                        'entretien' => $entretien,
                    ]
                ),
                'text/html'
            )

            ->addPart(
                $this->renderView(
                    'emails/nvEntretienRecruteur.txt.twig'
                ),
                'text/plain'
            );

        $mailer->send($messageToCandidat);
        $mailer->send($messageToRecruteur);
    }

    /**
     * @Route("/entretien/new/{id}", name="entretien_new", methods={"GET","POST"})
     */
    public function new(Candidat $candidat, Request $request, RecruteurRepository $recruteurRepo, SalleRepository $salleRepo): Response
    {
        $entretien = new Entretien();
        $entretien->setCandidat($candidat);
        $form = $this->createForm(EntretienType::class, $entretien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidatAnneesExp = $candidat->getProfil()->getNbAnneesExp();
            $candidatCompetences = $candidat->getProfil()->getCompetence()->toArray();
            $competenceArray = [];
            $recruteurDispo = null;
            foreach ($candidatCompetences as $competence) {
                $comp = $competence->getCompetence();
                array_push($competenceArray, $comp);
            }
            // Recuperer les recruteurs qui ont plus d'annees d'exp
            $recruteursPlusExp = $recruteurRepo->searchForAnneesExp($candidatAnneesExp);

            //Parmis ces recruteurs, recupere ceux qui ont plus de competences que candidats
            //date dispo 
            $dateEntretien = $request->request->get("entretien")["dateEntretien"];

            foreach ($recruteursPlusExp as $recruteurPlusExp) {

                foreach ($competenceArray as $competenceOne) {
                    $recruteurCompetenceOk = $recruteurRepo->findRecruteurCompetenceOk($recruteurPlusExp, $competenceOne, $dateEntretien);
                    if (!$recruteurCompetenceOk) {

                        break;
                    }
                }
                if ($recruteurCompetenceOk) {
                    $recruteurDispo = $recruteurCompetenceOk[0];
                    break;
                }
            }
            // dd($recruteurDispo);
            if ($recruteurDispo) {
                $entretien->setRecruteur($recruteurDispo);
                $capacityMin = 2;
                $dispo = 1;

                $salle = $salleRepo->findSaleForEntretien($capacityMin, $dispo, $dateEntretien);
                // dd($salle);


                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($entretien);
                $entityManager->flush();
                // $this->addFlash("NewEntretien" , "Entretien ajouté");
                return $this->redirectToRoute('entretien_index');
            } else {
                $this->addFlash("pasDeDisponibilite", "Aucun recruteur disponible!");
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
    public function edit(Request $request, Entretien $entretien, CalendarRepository $calendarRepo, RecruteurRepository $recruteurRepo, VisioconferenceRepository $visioconfRepo, SalleRepository $salleRepo, \Swift_Mailer $mailer): Response
    {
        //edit date
        $recruteur = $entretien->getRecruteur();
        $datesDispo = $calendarRepo->findDispo($recruteur);

        //edit recruteur
        $recruteurs = [];
        $candidatAnneesExp = $entretien->getCandidat()->getProfil()->getNbAnneesExp();
        $candidatCompetences = $entretien->getCandidat()->getProfil()->getCompetence()->toArray();
        $competenceArray = [];
        $recruteurDispo = [];
        foreach ($candidatCompetences as $competence) {
            $comp = $competence->getCompetence();
            array_push($competenceArray, $comp);
        }
        // Recuperer les recruteurs qui ont plus d'annees d'exp
        $recruteursPlusExp = $recruteurRepo->searchForAnneesExp($candidatAnneesExp);

        //Parmis ces recruteurs, recupere ceux qui ont plus de competences que candidats
        //date dispo 
        $dateEntretien = $entretien->getDateEntretien();
        // $rec=$recruteurRepo->findAllPossibleRecruteursDateAndExp($dateEntretien,$recruteursPlusExp);
        // dd($rec);
        foreach ($recruteursPlusExp as $recruteurPlusExp) {

            foreach ($competenceArray as $competenceOne) {
                $recruteurCompetenceOk = $recruteurRepo->findRecruteurCompetenceOk($recruteurPlusExp, $competenceOne, $dateEntretien);

                if (!$recruteurCompetenceOk) {
                    // $recruteurs=[];
                    break;
                }
            }
            if ($recruteurCompetenceOk) {
                $recruteurs[] = $recruteurCompetenceOk;
            }
        }

        if ($request->request->get("newDate") or $request->request->get("newRecruteur")) {
            $oldEntretienDate = $entretien->getDateEntretien();
            $newCalendar = new Calendar();
            $newCalendar->setStart($oldEntretienDate)
                ->setRecruteur($entretien->getRecruteur())
                ->setAllDay(1);

            if ($request->request->get("newDate")) {
                $getDate = $request->request->get("newDate");
                $newDate = \DateTime::createFromFormat('Y-m-d', $getDate);
                $entretien->setDateEntretien($newDate);
                $templateCandidat = 'emails/editEntretienCandidat.html.twig';
                $templateRecruteur = 'emails/editEntretienRecruteur.html.twig';
                $this->changeEntretienEmail($entretien, $mailer, $templateCandidat, $templateRecruteur);
            } elseif ($request->request->get("newRecruteur")) {
                $entretien->setRecruteur($recruteurRepo->find($request->request->get("newRecruteur")));
            }



            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newCalendar);
            $entityManager->persist($entretien);
            // $dispoToRemove=$calendarRepo->findalendar($entretien->getRecruteur(),$entretien->getDateEntretien());
            $dispoToRemove = $calendarRepo->findBy(
                ['recruteur' => $entretien->getRecruteur(), 'start' => $entretien->getDateEntretien()]
            );

            

            $entityManager->remove($dispoToRemove[0]);
            $entityManager->flush();
            $this->addFlash("EditEntretien", "Entretien modifié");
            return $this->redirectToRoute('entretien_index');
        }

        $isVisio = $request->request->get("isvisio");
        if (isset($isVisio)) {
            $visioconf = $visioconfRepo->findBy(array("entretien" => null));
            // dd($visioconf);
            if ($visioconf) {

                $entretien->getSalle()->setDisponible(1);
                $entretien->setSalle(null);
                $visioconf[0]->setEntretien($entretien);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($visioconf[0]);
                $entityManager->persist($entretien);

                $entityManager->flush();
                $templateCandidat = 'emails/editEntretienCandidat.html.twig';
                $templateRecruteur = 'emails/editEntretienRecruteur.html.twig';
                $this->changeEntretienEmail($entretien, $mailer, $templateCandidat, $templateRecruteur);
                $this->addFlash("EditEntretien", "Entretien modifié");
                return $this->redirectToRoute('entretien_index');
            } else {
                $this->addFlash("pasDeVisioConfLibre", "Aucune visioconférence n'est possible!");
                return $this->redirectToRoute('candidat_index');
            }
        }

        $isPresentiel = $request->request->get("ispresentiel");
        if (isset($isPresentiel)) {
            $capacityMin = 2;
            $dispo = 1;

            $salle = $salleRepo->findSaleForEntretien($capacityMin, $dispo, $dateEntretien);
            if ($salle) {
                $entretien->setSalle($salle);
                

                $salleDispoOff = $salleRepo->find($salle->getId());
                if ($salleDispoOff) {
                    $salleDispoOff->setDisponible(0);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($salleDispoOff);

                    $entretien->getVisioconference()->setEntretien(null);
                    $entityManager->persist($entretien);

                    $entityManager->flush();
                    $this->addFlash("EditEntretien", "Entretien modifié");
                    $templateCandidat = 'emails/editEntretienCandidat.html.twig';
                    $templateRecruteur = 'emails/editEntretienRecruteur.html.twig';
                    $this->changeEntretienEmail($entretien, $mailer, $templateCandidat, $templateRecruteur);
                    return $this->redirectToRoute('entretien_index');
                }
            } else {
                $this->addFlash("pasDeSalleLibre", "Aucune salle n'est libre!");
                return $this->redirectToRoute('candidat_index');
            }
        }



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
    public function newEntretien(Candidat $candidat, Request $request, RecruteurRepository $recruteurRepo, CalendarRepository $calendarRepo, SalleRepository $salleRepo, VisioconferenceRepository $visioconfRepo, MailerInterface $mailere, \Swift_Mailer $mailer): Response
    {
        $entretien = new Entretien();
        $entretien->setCandidat($candidat);
        $form = $this->createForm(EntretienType::class, $entretien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request->request);
            $candidatAnneesExp = $candidat->getProfil()->getNbAnneesExp();
            $candidatCompetences = $candidat->getProfil()->getCompetence()->toArray();
            $competenceArray = [];
            $recruteurDispo = null;
            foreach ($candidatCompetences as $competence) {
                $comp = $competence->getCompetence();
                array_push($competenceArray, $comp);
            }
            // Recuperer les recruteurs qui ont plus d'annees d'exp
            $recruteursPlusExp = $recruteurRepo->searchForAnneesExp($candidatAnneesExp);

            //Parmis ces recruteurs, recupere ceux qui ont plus de competences que candidats
            //date dispo 
            $dateEntretien = $request->request->get("entretien")["dateEntretien"];


            foreach ($recruteursPlusExp as $recruteurPlusExp) {

                foreach ($competenceArray as $competenceOne) {
                    $recruteurCompetenceOk = $recruteurRepo->findRecruteurDateOkCompetenceOk($recruteurPlusExp, $competenceOne, $dateEntretien);
                    // dd($recruteurCompetenceOk);
                    if (!$recruteurCompetenceOk) {

                        break;
                    }
                }
                if ($recruteurCompetenceOk) {
                    $recruteurDispo = $recruteurCompetenceOk[0];
                    break;
                }
            }
            $entityManager = $this->getDoctrine()->getManager();
            // dd($recruteurDispo);
            if ($recruteurDispo) {
                $entretien->setRecruteur($recruteurDispo);
                $dispoToRemove = $calendarRepo->findCalendar($recruteurDispo, $dateEntretien);

                //if visioconf
                $getVisio = $request->request->get("visioconf");
                if (isset($getVisio)) {
                    $visioconf = $visioconfRepo->findBy(array("entretien" => null));
                    // dd($visioconf);
                    if ($visioconf) {
                        $visioconf[0]->setEntretien($entretien);
                        $entretien->setVisioconference($visioconf[0]);
                        $entityManager->persist($visioconf[0]);
                    } else {
                        $this->addFlash("pasDeVisioConfLibre", "Aucune visioconférence n'est possible!");
                        return $this->redirectToRoute('candidat_index');
                    }
                } else {
                    $capacityMin = 2;
                    $dispo = 1;

                    $salle = $salleRepo->findSaleForEntretien($capacityMin, $dispo, $dateEntretien);
                    if ($salle) {
                        $entretien->setSalle($salle);

                        $salleDispoOff = $salleRepo->find($salle->getId());
                        if ($salleDispoOff) {
                            $salleDispoOff->setDisponible(0);
                            $entityManager->persist($salleDispoOff);
                        }
                    }
                }

                $entityManager->persist($entretien);
                // dd($entretien->getVisioconference());
                $entityManager->remove($dispoToRemove);
                $entityManager->flush();

                $templateCandidat = 'emails/nvEntretienCandidat.html.twig';
                $templateRecruteur = 'emails/nvEntretienRecruteur.html.twig';
                $this->changeEntretienEmail($entretien, $mailer, $templateCandidat, $templateRecruteur);

                $this->addFlash("NewEntretien", "Entretien ajouté, et des emails de confirmation sont envoyés au participants");

                return $this->redirectToRoute('entretien_index');
            } else {
                $this->addFlash("pasDeDisponibilite", "Aucun recruteur disponible!");
                return $this->redirectToRoute('candidat_index');
            }
        }

        return $this->render('entretien/new.html.twig', [
            'entretien' => $entretien,
            'form' => $form->createView(),
        ]);
    }
}
