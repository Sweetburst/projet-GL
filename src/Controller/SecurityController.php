<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
        $user = new User();
        $form= $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            if (!$user->getId()){
                // Si utilisateur pas inscrit on lui attribut une date d'inscription
                $user->setCreatedAt(new \DateTime());
            }

            $hash = $encoder->encodePassword($user, $user->getPassword()); //hashage du mdp avec l'algo bcrypt CF fichier Config->Packages->security.yaml
            $user->setPassword($hash);
            $user->setAdmin(FALSE);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login');

        }
        return $this->render('security/registration.html.twig', ['formUser' => $form->createView()]);
    }

    /**
     * @Route("/connexion", name="security_login",methods={"GET", "POST"})
     * pour la connexion d'un compte utilisateur
     */
    public function login(){
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/Deconnexion",name="security_logout")
     */
    public function Logout(){
        //le composant security manage le logout automatiquement 
    }

}
