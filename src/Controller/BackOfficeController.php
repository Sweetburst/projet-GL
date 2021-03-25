<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Profil;
use App\Entity\Allergene;
use App\Form\ProfileType;
use App\Form\UserBackType;
use App\Form\AllergeneType;
use App\Form\ProfileBackType;
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
     * @Route("/intermediaire",name="back_office")
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

        if( $requete->request->count()>0 ){
            $userInfo=$requete->request->get('user_back');
            dump($userInfo);
            $userTomodifieOrDelete=$repository->find($requete->request->get('id'));
            if(array_key_exists ( "modifier" , $userInfo )){
                $userTomodifieOrDelete->setNom($userInfo['nom']);
                $userTomodifieOrDelete->setPrenom($userInfo['prenom']);
                $userTomodifieOrDelete->setEmail($userInfo['email']);
                $userTomodifieOrDelete->setNumeroTelephone($userInfo['numeroTelephone']);
                $manager->persist($userTomodifieOrDelete);
                $manager->flush();
               
            }elseif(array_key_exists ( "supprimer" , $userInfo )){
                $manager->remove($userTomodifieOrDelete);
                $manager->flush();
            }
        }
      
        //$usersOnly=$repository->findBy(['admin'=> false]);
        $formsView= $this->createManageForms($users,$types);
        //dump($this->createManageForms($users,$types,$manager,$requete));
        $forms=$this->createForms($users,$types);

        //geration du formulaire doit etre obligatoirement dans le controller 
        //et n'ont pas dans une function separer pour pouvoir recuperer nouveau data directement
        
       
       
        // foreach ($forms as $key => $value) {
        //     //value represent a form
        //    dump($value);
        //     $id=-1;
        //     dump($requete->request->get('id'));
            
        //         $value->handleRequest($requete);
        //         if( $value->isSubmitted() && $value->isValid() ){
        //             //form remplir et valider
        //             //ump($value);
        //             //code modifier
        //             if( $value->get('modifier')->isClicked() ){
        //                 $manager->persist($userTomodifieOrDelete);
        //                 $manager->flush();
        //                 //pour relancer la page avec nouveau donne
        //                 return $this->redirectToRoute("back_office_user_or_admins",['types'=>$types]);
        //             } elseif ($value->get('supprimer')->isClicked()){
        //                // $manager->remove($userTomodifieOrDelete);
        //             }
        //         }
        //     $i=$i+1;
        // }
        $user=New User();//utilisateur vide
        //obtenir form pour creer nouveau compte
        $formRegistration=$this->createForm(RegistrationType::class, $user);
        //TODO gerer le form Registration type nouveau utilisateur
        $formRegistration->handleRequest($requete);
        if($formRegistration->isSubmitted() && $formRegistration->isValid()){
            dump($requete);
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
            //return $this->redirectToRoute("back_office_user_or_admins",['types'=>$types]);
            
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
 * backOfficeProfiles
 * @Route("/back_Office/profiles",name="back_office_profile")
 * page admin pour gerer les profiles
 * @param  mixed $requete get request from page when submiting,...
 * @param  mixed $manager entity manager to work with database
 * @return Response return the page to show
 */    
   
    public function backOfficeProfiles(Request $requete,EntityManagerInterface $manager): Response
    {
        $repository = $this->getDoctrine()->getRepository(Profil::class);
        $repositoryUsers=$this->getDoctrine()->getRepository(User::class);
        $repositoryAllergene=$this->getDoctrine()->getRepository(Allergene::class);
        if( $requete->request->count()>0 ){
             $ProfileInfo=$requete->request->get('profile_back');
             if(array_key_exists ( "creeProfile" , $ProfileInfo )){
                $newProfile=new Profil();
                $newProfile->setCreatedAt(new \DateTime())
                           ->setUser($repositoryUsers->find($ProfileInfo['user']))
                           ->setNom($ProfileInfo['nom'])
                           ->setPrenom($ProfileInfo['prenom'])
                           ->setAge($ProfileInfo['age']);
                if(array_key_exists ( "allergenes" , $ProfileInfo )){
                    $tabAllergene=$ProfileInfo['allergenes'];
                    dump($tabAllergene);
                    //add the allergene selectioner to the profile
                    for ($i=0;$i<count($tabAllergene);$i++){
                        $newProfile->addAllergene($repositoryAllergene->find($tabAllergene[$i]));
                    }
                }
                $manager->persist($newProfile);
                $manager->flush();
             }
            
             if(array_key_exists ( "modifier" , $ProfileInfo )){
                $ProfileTomodifieOrDelete=$repository->find($requete->request->get('id'));
                $ProfileTomodifieOrDelete->setNom($ProfileInfo['nom']);
                $ProfileTomodifieOrDelete->setPrenom($ProfileInfo['prenom']);
                $ProfileTomodifieOrDelete->setAge($ProfileInfo['age']);
                $ProfileTomodifieOrDelete->setAge($ProfileInfo['user']);
              //  $ProfileTomodifieOrDelete->setNumeroTelephone($ProfileInfo['numeroTelephone']);
                if(array_key_exists ( "allergenes" , $ProfileInfo )){
                    $tabAllergene=$ProfileInfo['allergenes'];
                    dump($tabAllergene);
                    //get all allergenes in database
                    $allergenes=$repositoryAllergene->findall();
                    //remove all previeus allergene if dont do it when removeing the selectioned allergene will not persist
                    foreach ($allergenes as $key => $value) {
                        $ProfileTomodifieOrDelete->removeAllergene($value);
                    }
                    //add the allergene selectioner 
                    for ($i=0;$i<count($tabAllergene);$i++){
                          $ProfileTomodifieOrDelete->addAllergene($repositoryAllergene->find($tabAllergene[$i]));
                    }
                }
                $manager->persist($ProfileTomodifieOrDelete);
                $manager->flush();
               
             }elseif(array_key_exists ( "supprimer" , $ProfileInfo )){
                $ProfileTomodifieOrDelete=$repository->find($requete->request->get('id'));
                $manager->remove($ProfileTomodifieOrDelete); //delete profile selectioned
                $manager->flush();//delete from  database
            }
        }

        $profiles=$repository->findall();//get all profile

        //get les form pour les affichage de donner dans le tableau
        $forms=$this->createForms($profiles,"profiles"); 
        $formsView=$this->createManageForms($profiles,"profiles");
        //TODO gerer les forms d'affichage
        //form de creation nouveau profile
        $prof = new Profil();//profil vide
        $formRegistration=$this->createForm(ProfileBackType::class, $prof);
        $formRegistration->handleRequest($requete);
        //dump($formRegistration->isSubmitted());
        // if($formRegistration->isSubmitted() && $formRegistration->isValid()){
        //     if($formRegistration->get('modifier')->isClicked()){
        //         if (!$prof->getId()){
        //             // Si utilisateur pas inscrit on lui attribut une date d'inscription
        //             $prof->setCreatedAt(new \DateTime());
        //         }
        //        // $manager->persist($prof);
        //        // $manager->flush();
        //        // return $this->redirectToRoute("back_office_profile");//get back to page and see new profile
        //     }
        // }

        //rendering the page  
        return $this->render('back_office/profil_back_office.html.twig',[
            'forms' => $formsView,
            'formRegistration' => $formRegistration->createView()
        ]);  
    }





/**
 * backOfficeAllergene
 * @Route("/back_Office/allergenes",name="back_office_allergene")
 * page admin pour gerer les profiles
 * @param  mixed $requete get request from page when submiting,...
 * @param  mixed $manager entity manager to work with database
 * @return Response return the page to show
 */    
   
public function backOfficeAllergene(Request $requete,EntityManagerInterface $manager): Response
{
    //repository that will contain our allergene from database
    $repository = $this->getDoctrine()->getRepository(Allergene::class);
    //$repositoryUsers=$this->getDoctrine()->getRepository(User::class);
   // $repositoryAllergene=$this->getDoctrine()->getRepository(Allergene::class);
    dump($requete);
    if( $requete->request->count()>0 ){
        dump($requete);
         $AllergeneInfo=$requete->request->get('allergene');
         if(array_key_exists ( "creeNewAllergene" , $AllergeneInfo )){
            $newAllergene=new Allergene();
            $newAllergene->setCreatedAt(new \DateTime())
                       ->setNomAllergene($AllergeneInfo['nom_allergene'])
                       ->setDescription($AllergeneInfo['description']);
            $manager->persist($newAllergene);
            $manager->flush();
         }
        
         if(array_key_exists ( "modifier" , $AllergeneInfo )){
            $AllergeneTomodifieOrDelete=$repository->find($requete->request->get('id'));
            $AllergeneTomodifieOrDelete->setNomAllergene($AllergeneInfo['nom_allergene']);
            $AllergeneTomodifieOrDelete->setDescription($AllergeneInfo['desscription']);
            $AllergeneTomodifieOrDelete->setCreatedAt($AllergeneInfo['createdAt']);
          //  $ProfileTomodifieOrDelete->setNumeroTelephone($ProfileInfo['numeroTelephone']);
            $manager->persist($AllergeneTomodifieOrDelete);
            $manager->flush();
           
         }elseif(array_key_exists ( "supprimer" , $AllergeneInfo )){
            $AllergeneTomodifieOrDelete=$repository->find($requete->request->get('id'));
            $manager->remove($AllergeneTomodifieOrDelete);
            $manager->flush();
        }
    }

    $allergenes=$repository->findall();

    //get les form pour les affichage de donner dans le tableau
    $forms=$this->createForms($allergenes,"allergenes"); 
    $formsView=$this->createManageForms($allergenes,"allergenes");
    //TODO gerer les forms d'affichage
    //form de creation nouveau profile
    $allerg = new Allergene();//profil vide
    dump($allerg);
    $formRegistration=$this->createForm(AllergeneType::class, $allerg);
   // $formRegistration->handleRequest($requete);

    //rendering the page  
    return $this->render('back_office/Allergene_back_office.html.twig',[
        'forms' => $formsView,
        'formRegistration' => $formRegistration->createView()
    ]);  
}



























/********************************function to reduce redundance*******************************************/


    /**
     * createManageForms
     * DESCRIPTION:function that create multiple form and manage them
     * for back_office page and return collection of form because multiple object to show together
     * @param  mixed $data data contains all object of entity
     * @param  mixed $type string that specifie the type of forms
     * @return void return a table contains that multiple forms
     */
    public function createManageForms($data,$type){
        //$data=$repository->findAll();
        //dump($forms1);
        //dump($data);
       
        $formView=[];
        $i=0;
        foreach ($data as $key => $value) {
            # code...
            switch($type){
                //casse aadmin and users are the same
                case "admins":
                case "users":
                        
                        $formsView[$i]=$this->createForm(UserBackType::class, $value)->createview();
                        $i=$i+1;
                        break;
                
                case "profiles":
                       
                        $formsView[$i]=$this->createForm(ProfileBackType::class, $value)->createview();
                        $i=$i+1;
                        break;
                case "allergenes":
                        
                       
                        $formsView[$i]=$this->createForm(AllergeneType::class, $value)->createview();
                        $i=$i+1;
                        break;
                                    
                case "scans":
                        
                        $formsView[$i]=$this->createForm(ScanBackType::class, $value)->createview();
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
     * @return void les forms a gerer
     */
    public function createForms($data,$type){
        //$data=$repository->findAll();
        //dump($forms1);
       // dump($data);
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
                        $forms[$i]=$this->createForm(ProfileBackType::class, $value);
                        $i=$i+1;
                        break;
                case "allergenes":
                        $forms[$i]=$this->createForm(AllergeneType::class, $value);
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
