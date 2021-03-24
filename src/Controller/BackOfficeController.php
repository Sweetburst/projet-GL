<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Profil;
use App\Form\ProfileType;
use App\Form\UserBackType;
use App\Form\RegistrationType;
use App\Form\UserInfoPersonnelleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BackOfficeController extends AbstractController
{
    /**
     * @Route("/backOffice",name="back_office")
     */
    public function index(): Response
    {
        return $this->render('back_office/index.html.twig', [
            'controller_name' => 'BackOfficeController',
        ]);
    }

     /**
     * @Route("/backOffice/test",name="back_office_test")
     */
    public function index1(): Response
    {
        return $this->render('back_office/test.html.twig', [
            'controller_name' => 'BackOfficeController',
        ]);
    }

    /**
     * @Route("/backOffice/{types}",name="back_office_user_or_admins")
     * page admin pour gerer les utilisateur et autre admin
     */
    public function backOfficeUser(Request $requete,EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder,String $types): Response
    {
        
        //initialisation du repository du user
        //$repositoryUsers = $this->getDoctrine()->getRepository(User::class);

        /*
        //obtenir tout les utilisateurs
        $users=$repositoryUsers->findAll();
        //dump($forms1);
        //remplacer par fonction
        $forms=[];
        $formView=[];
        $i=0;
        foreach ($users as $key => $value) {
            # code...
            $forms[$i]=$this->createForm(UserBackType::class, $value);
            $formsView[$i]=$this->createForm(UserBackType::class, $value)->createview();
            $i=$i+1;
        }
        //$this->createForm(ProfileType::class, $profile);
        //dump($forms);
        dump($requete);
        //gerer le formulaire 
        $i=0;
        foreach ($forms as $key => $value) {
            //value represent a form
            $value->handleRequest($requete);
            if( $value->isSubmitted() && $value->isValid() ){
                //form remplir et valider
                dump($value);
                //code modifier
                if($value->get('modifier')->isClicked()){
                    dump($requete);
                    dump($users[$i]);
                    $manager->persist($users[$i]);
                    $manager->flush();
                    //pour relancer la page avec nouveau donne
                    return $this->redirectToRoute("back_office_user");
                }
               elseif($value->get('supprimer')->isClicked()){
                    //$manager->remove($user);
               }

            }
            $i=$i+1;
        }
        */
        if($types!='admins' or $types!='users'){
           // return $this->render('error_page.html.twig');
        }
        $repository = $this->getDoctrine()->getRepository(User::class);
        if($types=='users'){
            $users=$repository->findBy(['admin'=> false]);
        } else {
            $users=$repository->findBy(['admin'=> TRUE]);
        }
        
        //$usersOnly=$repository->findBy(['admin'=> false]);
        $formsView= $this->createManageForms($users,$types,$manager,$requete);
        dump($this->createManageForms($users,$types,$manager,$requete));
        $forms=$this->createForms($users,$types,$manager,$requete);

        //geration du formulaire doit etre obligatoirement dans le controller 
        //et n'ont pas dans une function separer pour pouvoir recuperer nouveau data directement
        $i=0;
        foreach ($forms as $key => $value) {
            //value represent a form
            $value->handleRequest($requete);
            if( $value->isSubmitted() && $value->isValid() ){
                //form remplir et valider
                //ump($value);
                //code modifier
                if($value->get('modifier')->isClicked()){
                    dump($requete);
                    $manager->persist($users[$i]);
                    $manager->flush();
                    //pour relancer la page avec nouveau donne
                    return $this->redirectToRoute("back_office_user_or_admins");
                }
               elseif($value->get('supprimer')->isClicked()){
                    $manager->remove($data);
               }

            }
            $i=$i+1;
        }
        $user=New User();//utilisateur vide
        //obtenir form pour creer nouveau compte
        $formRegistration=$this->createForm(RegistrationType::class, $user);
        //TODO gerer le form Registration type nouveau utilisateur
        $formRegistration->handleRequest($requete);
        if($formRegistration->isSubmitted() && $formRegistration->isValid()){

            if (!$user->getId()){
                // Si utilisateur pas inscrit on lui attribut une date d'inscription
                $user->setCreatedAt(new \DateTime());
            }

            $hash = $encoder->encodePassword($user, $user->getPassword()); //hashage du mdp avec l'algo bcrypt CF fichier Config->Packages->security.yaml
            $user->setPassword($hash);
            if($types=='users'){
                $user->setAdmin(FALSE);
            } else {
                $user->setAdmin(TRUE);
            } 
            $manager->persist($user);
            $manager->flush();
            //redirection sur la mem page avec nouveau utilisateur
            return $this->redirectToRoute("back_office_user_or_admins");
            
        }

        
        //reendering to the page
        return $this->render('back_office/admin_user.html.twig', [
            //'users'=>$users,
            'forms' => $formsView,
            'formRegistration' => $formRegistration->createView(),
            'type'=>$types
        ]);
    }

    
/**
 * backOfficeAdmins
 * @Route("/backOffice/admins",name="back_office_admin")
 * page admin pour gerer les admins 
 * @param  mixed $requete get request from page when submiting,...
 * @param  mixed $manager entity manager to work with database
 * @return Response return the page to show
 */    
   
    public function backOfficeAdmins(Request $requete,EntityManagerInterface $manager): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $admins=$repository->findall();
        //get the form and their view
        $formsView=$this->createManageForms($admins,"admin",$manager,$requete);
        $forms=$this->createForms($admins,"admin",$manager,$requete); 


        //TODO gerer les forms


        //rendering the page  
        return $this->render('back_office/admin_admin.html.twig',[
            'forms' => $formsView,
        ]);  
    }



/**
 * backOfficeProfiles
 * @Route("/backOffice/profiles",name="back_office_profile")
 * page admin pour gerer les profiles
 * @param  mixed $requete get request from page when submiting,...
 * @param  mixed $manager entity manager to work with database
 * @return Response return the page to show
 */    
   
    public function backOfficeProfiles(Request $requete,EntityManagerInterface $manager): Response
    {
        $repository = $this->getDoctrine()->getRepository(Profil::class);
        $profiles=$repository->findall();
        //get the form and their view
        $formsView=$this->createManageForms($profiles,"profiles",$manager,$requete);
        $forms=$this->createForms($profiles,"admin",$manager,$requete); 
        //TODO gerer les forms
        //rendering the page  
        return $this->render('back_office/admin_profiles.html.twig',[
            'forms' => $formsView,
        ]);  
    }































/********************************function to reduce redundance*******************************************/


    /**
     * createManageForms
     * DESCRIPTION:function that create multiple form and manage them
     * for back_office page and return collection of form because multiple object to show together
     * @param  mixed $data data contains all object of entity
     * @param  mixed $type string that specifie the type of forms
     * @param  mixed $Entitymanager entity manger to manage the database
     * @param  Request $requete request contain info of form after submited
     * @return void return a table contains that multiple forms
     */
    public function createManageForms($data,$type,$Entitymanager,$requete){
        //$data=$repository->findAll();
        //dump($forms1);
        dump($data);
        $forms=[];
        $formView=[];
        $i=0;
        foreach ($data as $key => $value) {
            # code...
            switch($type){
                //casse aadmin and users are the same
                case "admins":
                case "users":
                        $forms[$i]=$this->createForm(UserBackType::class, $value);
                        $formsView[$i]=$this->createForm(UserBackType::class, $value)->createview();
                        $i=$i+1;
                        break;
                
                case "profiles":
                        $forms[$i]=$this->createForm(ProfileType::class, $value);
                        $formsView[$i]=$this->createForm(ProfileType::class, $value)->createview();
                        $i=$i+1;
                        break;
                case "allergenes":
                        $forms[$i]=$this->createForm(AllergeneType::class, $value);
                        $formsView[$i]=$this->createForm(AllergeneType::class, $value)->createview();
                        $i=$i+1;
                        break;
                                    
                case "scans":
                        $forms[$i]=$this->createForm(AllergeneType::class, $value);
                        $formsView[$i]=$this->createForm(AllergeneType::class, $value)->createview();
                        $i=$i+1;
                        break;
                default:return -1;//error interdit
            }
        }
        return $formsView;
    }

    
    /**
     * createForms
     *
     * @param  mixed $data
     * @param  mixed $type
     * @param  mixed $Entitymanager
     * @param  mixed $requete
     * @return void les forms a gerer
     */
    public function createForms($data,$type,$Entitymanager,$requete){
        //$data=$repository->findAll();
        //dump($forms1);
        dump($data);
        $forms=[];
        $formView=[];
        $i=0;
        foreach ($data as $key => $value) {
            # code...
            switch($type){
                //casse aadmin and users are the same
                case "admins":
                case "users":
                        $forms[$i]=$this->createForm(UserBackType::class, $value);
                        $i=$i+1;
                        break;
                case "profiles":
                        $forms[$i]=$this->createForm(UserBackType::class, $value);
                        $i=$i+1;
                        break;
                case "allergenes":
                        $forms[$i]=$this->createForm(UserBackType::class, $value);
                        $i=$i+1;
                        break;
                case "scans":
                        $forms[$i]=$this->createForm(UserBackType::class, $value);
                        $i=$i+1;
                        break;
                default:return -1;//error interdit
            }
        }
        return $forms;
    }



}
