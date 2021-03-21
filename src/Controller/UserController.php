<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\Allergene;
use App\Repository\AllergeneRepository;
use App\Form\ProfileType;
use App\Form\RegistrationType;
use App\Form\UserChangePassType;
use App\Form\UserInfoPersonnelleType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/user/profiles", name="user_profiles")
     * page permettant de gerer les different profile dans un compte 
     */
    public function profile(): Response
    {
        return $this->render("site/profileManagement.html.twig");
    }

    /**
     * @Route("/user/account", name="user_account")
     * page pour gerer information du compte comme changer mot de passe modifier information personnelle
     */
    public function editInformation(Request $requete,EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder): Response
    {
        
    //check if user is connected if not go automaticly to connexion page
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    /** @var \App\Entity\User $user */
    // returns your User object, or null if the user is not authenticated
    $user = $this->getUser();
    
    //creer un form qui a les property du user 
    $form=$this->createForm(UserInfoPersonnelleType::class, $user);
    $formMotPass=$this->createForm(UserChangePassType::class, $user);
    
    $form->handleRequest($requete);
    $formMotPass->handleRequest($requete);
    dump($requete);
    if( $form->isSubmitted() && $form->isValid() ){  
        //$user->setAdmin(FALSE);
        $manager->persist($user);
        $manager->flush();
    }
    if( $formMotPass->isSubmitted() && $formMotPass->isValid() ){  
        //$user->setAdmin(FALSE);
        $hash = $encoder->encodePassword($user, $user->getPassword()); //hashage du mdp avec l'algo bcrypt CF fichier Config->Packages->security.yaml
        $user->setPassword($hash);
        $manager->persist($user);
        $manager->flush();
    }


        return $this->render("user/account.html.twig",[
            'formUser'=>$form->createView(),
            'formUserMotPass'=>$formMotPass->createView()
        ]);
    }

    /**
     *@Route("/user/creerProfile",name="creer_profile")
     *@Route("/user/{id}/edit",name="edit_profile")
     */

    public function creerProfile(Profil $profile = null,Request $requete,EntityManagerInterface $manager): Response
    {
        //create empty profile 
        if(!$profile){
            $profile=new Profil();
        }

        // $allergene=['sucre','salt','lait'];
        

        //check if user is connected
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //get user information
        $user = $this->getUser();
        //create form that will work on adding new profile and handlerequest to get data from form and add them to our instance object of profile
        $form=$this->createForm(ProfileType::class, $profile);
        $form->handleRequest($requete);//analyse the http request

        //initialisation du repository du allergene qui permettre de recupere inforamtion d'allergene
        $repository = $this->getDoctrine()->getRepository(Allergene::class); 

        //check if form is submitted and validated
        if( $form->isSubmitted() && $form->isValid() ){
            dump($requete);
            //code after form is validated and submited
            if(!$profile->getId()){ 
                //si nouveau profile 
                $tabAlllergene=[];//intialiser tableau vide qui va contenir id des allergene selectioner
                //TODO changer nom du parametre allergene 2 plus tard
                $profileReceivedAsArray=$requete->request->get('profile');//received from request
                $tabAllergene=$profileReceivedAsArray['allergenes2'];//an array of allergene received from the form in request
                for ($i=0;$i<count($tabAllergene);$i++){
                    $profile->addAllergene($repository->find($tabAllergene[$i]));
                } 
                $profile->setCreatedAt(new \DateTime())
                        ->setUser($user);
                       // ->setAllergene
            }  else {
                $allergenes=$profile->getAllergenes();
            }  
           
            $manager->persist($profile);
            $manager->flush();    
            //return $this->redirectToRoute("user_profiles");
        }
        
        //voir qu'elle liste d'allergene a afficher
        if(!$profile){
            //cas en page de creation d'un profile
            $allergenes = $repository->findAll();//liste de toute les allergene qu'on a dans notre base de donne
        } else {
            //cas en page de edit
            $allergenes=$profile->getAllergenes();
        }
       
        //dump($allergenes);

        //reponse avec editmode pour voir si on ait dans le cas de creation ou en etat de modification
        return $this->render("user/creerProfile.html.twig",[
            'user' => $user, 
            'formProfile' => $form->createView(),
            'editMode' => $profile->getId()!== null,
            'allergene'=> $allergenes
            
        ]);
    }

    /**
     * @Route("/user/editPassword",name="editPassword")
     */
    public function editPAssword():Response {

        //check if user is connected
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //get user information
        $user = $this->getUser();

        //TODO creer form pour changer mot de passer

        
        return $this->render("user/editPasword.html.twig",[
            'user' => $user
        ]);
    }

}
