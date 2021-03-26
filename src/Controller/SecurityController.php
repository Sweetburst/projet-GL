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

/**
 * SecurityController
 * controller qui gere notre connection au site authentification
 */
class SecurityController extends AbstractController
{
       
    /**
     * registration
     * Route :/inscription
     * name of Route:security_registration
     * @Route("/inscription", name="security_registration")
     * @param  mixed $request pour les requete envoyer en POST ou GET
     * @param  mixed $manager Pour manager la base de donne 
     * @param  mixed $encoder BCRYPT encoder pour coder le mot de passe
     * @return void
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
        $user = new User();//creer utilisateur vide
        //creer form qui va lire les information du nouveau utilisateur
        $form= $this->createForm(RegistrationType::class, $user);
        //ajout ke handlerequest qui va gerer le request
        $form->handleRequest($request);
        //voir si le form a etaitsubmiter et valider
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
     * login
     * pour la connexion d'un compte utilisateur
     * @Route("/connexion", name="security_login",methods={"GET", "POST"})
     * @return void
     */
    public function login(){
        //le composant security gere le login automatiquement
        return $this->render('security/login.html.twig');
    }




    
        
    /**
     * Logout
     * se deconnecter du compte
     * 
     * Route :/Deconnexion
     * 
     * name of Route:security_logout;
     * @Route("/Deconnexion",name="security_logout")
     * @return void
     */
    public function Logout(){
        //le composant security manage le logout automatiquement 
    }

}
