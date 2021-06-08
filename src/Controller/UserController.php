<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/inscription", name="user_inscription")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY') == false")
     */
    public function inscription(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User;
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
       

        if($form->isSubmitted() && $form->isValid())
        {
            $hash= $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);
            $user->setRoles(['ROLE_RECRUTEUR']);

            $manager->persist($user);
            $manager->flush();
            $this->addFlash("inscriptionUser","Bienvenu ".$user->getEmail()."!");
            return $this->redirectToRoute("user_connexion");

        }
        return $this->render('user/inscription.html.twig', [
            "formUser" => $form->createView()
         
        ]);
    }
    /**
     * @Route("/user/connexion", name="user_connexion")
     * @Security("is_granted('ROLE_USER') == false or is_granted('ROLE_ADMIN') == false")
     */
    public function connexion(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        // $user = new User;
        // $form = $this->createForm(UserType::class, $user);

        // $form->handleRequest($request);
       

        // if($form->isSubmitted() && $form->isValid())
        // {
            
            
        //     return $this->redirectToRoute("produit_afficher");

        // }

        return $this->render('user/connexion.html.twig');
    }

    /**
     * @Route("/logout", name="logout")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY') == true")
     */
    public function logout(){

    }

    /**
     * @Route("/roles", name="roles")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY') == true")
     */
    public function roles(){
        if($this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('acceuil');
        }
        elseif($this->isGranted('ROLE_RECRUTEUR')){
            return $this->redirectToRoute('acceuil');
        }
    }

    /**
     * @Route("/profile/{id}", name="recruteur_user_profile")
     */
    public function profileUserRecruteur(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User;
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
       

        if($form->isSubmitted() && $form->isValid())
        {
            $hash= $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);
            $user->setRoles(['ROLE_RECRUTEUR']);

            $manager->persist($user);
            $manager->flush();
            $this->addFlash("inscriptionUser","Bienvenu ".$user->getEmail()."!");
            return $this->redirectToRoute("user_connexion");

        }
        return $this->render('user/inscription.html.twig', [
            "formUser" => $form->createView()
         
        ]);
    }

}
