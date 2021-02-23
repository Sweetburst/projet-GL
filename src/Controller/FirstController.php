<?php


namespace App\Controller;


use App\Entity\User;
use PHPZxing\PHPZxingDecoder;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
<<<<<<< HEAD
//use PHPZxing\PHPZxingDecoder;
use TarfinLabs\ZbarPhp\Exceptions\InvalidFormat;
use TarfinLabs\ZbarPhp\Exceptions\UnableToOpen;
use TarfinLabs\ZbarPhp\Zbar;
use TarfinLabs\ZbarPhp;
use Khanamiryan\QrCodeTests;
use Zxing;
=======

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\RegistrationType;

>>>>>>> 672842f16bddc2b838028f7925f13e0274ca6ec3

class FirstController extends AbstractController
{
    
    /**
     * @Route("/connect",name="connection")
     */
    public function connexion(Request $requete){
        dump($requete);
        $repo=$this->getDoctrine()->getRepository(User::class);

        if($requete->request->count()>0){ //on peut mettre 3 au lieu de 0 //inutile il envoie vide//faut faire de validation avant le submit.
            //on a eentrer des parametre nom utilisateur et mot de passe
            $username=$requete->request->get('username');
            $pass=$requete->request->get('password');
            $user=$repo->findByNom($username);
            if(sizeof($user)>0)//assure que on a trouver utilisateur
                if($pass==$user[0]->getPassword()){
                 //   mot de pass correct code de redirection a la page d'acceuil en status connecter
                }
            else{
                //nom utilisateur n'existe pas pas de compte pour ce mail ou nom utilisateur
            }

        }

        return $this->render("site/connexion.html.twig");
    }
    /**
     * @Route("/crCompte",name="creerCompte")
     */
    public function creeCompte(Request $requete,EntityManagerInterface $manager){
        dump($requete);
        if($requete->request->count()>0){
            $user=new User();
            $user->setEmail($requete->request->get('email'))
                 ->setNom($requete->request->get('nom'))
                 ->setPrenom($requete->request->get('prenom'))
                 ->setNumeroTelephone($requete->request->get('telephone'))
                 ->setPassword($requete->request->get('password_1'))
                 ->setCreatedAt(new \DateTime());

            $manager->persist($user);
            $manager->flush();
        }


        return $this->render("site/creerCompte.html.twig");
    }

    /**
<<<<<<< HEAD
     * @Route("/demo",name="demoPage")
     */
    public function BarcodeDemoPage(){

    
        return $this->render("site/demo.html.twig");
=======
     * @Route("/editUser", name="editUser")
     */
    public function edit_user(){
        return $this->render("site/editUser.html.twig");
    }

    /**
     * @Route("/accueil", name="accueil")
     */
    public function manageUser(){
        return $this->render("site/accueil.html.twig");
>>>>>>> 672842f16bddc2b838028f7925f13e0274ca6ec3
    }


}