<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Profil;
use App\Entity\Allergene;
use App\Form\ProfileType;
use App\Form\ProfileBackType;
use App\Form\ProfileGroupType;
use App\Form\RegistrationType;
use App\Form\ProfileScannerType;
use App\Form\UserChangePassType;
use App\Form\UserInfoPersonnelleType;
use App\Repository\AllergeneRepository;
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
    public function profile(Request $requete, EntityManagerInterface $manager): Response
    {
        //check if user is connected if not go automaticly to connexion page
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var \App\Entity\User $user */
        // returns your User object, or null if the user is not authenticated
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(Profil::class);
        $repositoryUsers = $this->getDoctrine()->getRepository(User::class);
        $repositoryAllergene = $this->getDoctrine()->getRepository(Allergene::class);
        dump($requete);
        if ($requete->request->count() > 0) {
            $ProfileInfo = $requete->request->get('profile_back');
            //cas creer nouveau profile
            if (array_key_exists("creeProfile", $ProfileInfo)) {
                $newProfile = new Profil();
                $newProfile->setCreatedAt(new \DateTime())
                    ->setUser($repositoryUsers->find($user))
                    ->setNom($ProfileInfo['nom'])
                    ->setPrenom($ProfileInfo['prenom'])
                    ->setAge($ProfileInfo['age']);
                if (array_key_exists("allergenes", $ProfileInfo)) {
                    $tabAllergene = $ProfileInfo['allergenes'];
                    dump($tabAllergene);
                    //add the allergene selectioner to the profile
                    for ($i = 0; $i < count($tabAllergene); $i++) {
                        $newProfile->addAllergene($repositoryAllergene->find($tabAllergene[$i]));
                    }
                }
                $manager->persist($newProfile);
                $manager->flush();
            }
            //cas modifier
            if (array_key_exists("modifier", $ProfileInfo)) {
                $ProfileTomodifieOrDelete = $repository->find($requete->request->get('id'));
                $ProfileTomodifieOrDelete->setNom($ProfileInfo['nom']);
                $ProfileTomodifieOrDelete->setPrenom($ProfileInfo['prenom']);
                //$ProfileTomodifieOrDelete->setAge($ProfileInfo['age']);
                $ProfileTomodifieOrDelete->setUser($user);
                //  $ProfileTomodifieOrDelete->setNumeroTelephone($ProfileInfo['numeroTelephone']);
                if (array_key_exists("allergenes", $ProfileInfo)) {
                    $tabAllergene = $ProfileInfo['allergenes'];
                    dump($tabAllergene);
                    //get all allergenes in database
                    $allergenes = $repositoryAllergene->findall();
                    //remove all previeus allergene if dont do it when removeing the selectioned allergene will not persist
                    foreach ($allergenes as $key => $value) {
                        $ProfileTomodifieOrDelete->removeAllergene($value);
                    }
                    //add the allergene selectioner 
                    for ($i = 0; $i < count($tabAllergene); $i++) {
                        $ProfileTomodifieOrDelete->addAllergene($repositoryAllergene->find($tabAllergene[$i]));
                    }
                }
                $manager->persist($ProfileTomodifieOrDelete);
                $manager->flush();
                // cas supprimer
            } elseif (array_key_exists("supprimer", $ProfileInfo)) {
                $ProfileTomodifieOrDelete = $repository->find($requete->request->get('id'));
                $manager->remove($ProfileTomodifieOrDelete); //delete profile selectioned
                $manager->flush(); //delete from  database
            }
        }

        $profiles = $repository->findby(['user' => $user]);

        $formsView = $this->createManageForms($profiles, "profiles");



        $prof = new Profil(); //profil vide
        $formRegistration = $this->createForm(ProfileBackType::class, $prof);
        $formRegistration->handleRequest($requete);

        $user = $this->getUser();

        dump($profiles);
        return $this->render("user/profileGerer.html.twig", [
            'forms' => $formsView,
            'formRegistration' => $formRegistration->createView()
        ]);
    }







    /**
     * @Route("/user/account", name="user_account")
     * page pour gerer information du compte comme changer mot de passe modifier information personnelle
     */
    public function editInformation(Request $requete, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder): Response
    {

        //check if user is connected if not go automaticly to connexion page
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var \App\Entity\User $user */
        // returns your User object, or null if the user is not authenticated
        $user = $this->getUser();

        //creer un form qui a les property du user 
        $form = $this->createForm(UserInfoPersonnelleType::class, $user);
        $formMotPass = $this->createForm(UserChangePassType::class, $user);

        $form->handleRequest($requete);
        $formMotPass->handleRequest($requete);
        dump($requete);
        if ($form->isSubmitted() && $form->isValid()) {
            //$user->setAdmin(FALSE);
            $manager->persist($user);
            $manager->flush();
        }
        if ($formMotPass->isSubmitted() && $formMotPass->isValid()) {
            //$user->setAdmin(FALSE);
            $hash = $encoder->encodePassword($user, $user->getPassword()); //hashage du mdp avec l'algo bcrypt CF fichier Config->Packages->security.yaml
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();
        }


        return $this->render("user/account.html.twig", [
            'formUser' => $form->createView(),
            'formUserMotPass' => $formMotPass->createView()
        ]);
    }

    /**
     *@Route("/user/creerProfile",name="creer_profile")
     *@Route("/user/{id}/edit",name="edit_profile")
     */

    public function creerProfile(Profil $profile = null, Request $requete, EntityManagerInterface $manager): Response
    {
        //create empty profile 
        if (!$profile) {
            $profile = new Profil();
        }

        // $allergene=['sucre','salt','lait'];


        //check if user is connected if not redirected to connexion page
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //get user information
        $user = $this->getUser();
        //create form that will work on adding new profile and handlerequest to get data from form and add them to our instance object of profile
        $form = $this->createForm(ProfileType::class, $profile);

        $form->handleRequest($requete); //analyse the http request

        //initialisation du repository du allergene qui permettre de recupere inforamtion d'allergene
        $repository = $this->getDoctrine()->getRepository(Allergene::class);
        dump($requete);
        //check if form is submitted and validated
        if ($form->isSubmitted() && $form->isValid()) {
            dump($requete);
            //code after form is validated and submited
            if (!$profile->getId()) {
                //si nouveau profile 
                $tabAlllergene = []; //intialiser tableau vide qui va contenir id des allergene selectioner
                //TODO changer nom du parametre allergene 2 plus tard
                $profileReceivedAsArray = $requete->request->get('profile'); //received from request as associative array
                $tabAllergene = $profileReceivedAsArray['allergenes2']; //an array of allergene received from the form in request
                for ($i = 0; $i < count($tabAllergene); $i++) {
                    $profile->addAllergene($repository->find($tabAllergene[$i])); //add selectioned array
                }
                $profile->setCreatedAt(new \DateTime())
                    ->setUser($user);
                // ->setAllergene
            } else {
                $allergenes = $profile->getAllergenes();
            }

            $manager->persist($profile);
            $manager->flush();
            //return $this->redirectToRoute("user_profiles");
        }

        //voir qu'elle liste d'allergene a afficher
        if (!$profile) {
            //cas en page de creation d'un profile
            $allergenes = $repository->findAll(); //liste de toute les allergene qu'on a dans notre base de donne
        } else {
            //cas en page de edit
            $allergenes = $profile->getAllergenes();
        }

        //dump($allergenes);
        //TODO maybe need to change allergene to json ithink i dont need not sure test it
        //reponse avec editmode pour voir si on ait dans le cas de creation ou en etat de modification
        return $this->render("user/creerEditProfile.html.twig", [
            'user' => $user,
            'formProfile' => $form->createView(),
            'editMode' => $profile->getId() !== null,
            'allergene' => $allergenes
        ]);
    }

    /**
     * @Route("/user/editPassword",name="editPassword")
     */
    public function editPAssword(): Response
    {

        //check if user is connected
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //get user information
        $user = $this->getUser();

        //TODO creer form pour changer mot de passer ajout ancien mot de passe peut etre last thing


        return $this->render("user/editPasword.html.twig", [
            'user' => $user
        ]);
    }



    /**
     * scanner
     * page pour scanner les produit
     * @Route("/user/scanner",name="user_scan")
     * 
     * @return Response toShow the twig page with the sended Data
     *  profils contains list of profiles related to user
     *  forms contains list of forms related to profiles 
     */
    public function scanner(Request $requete, EntityManagerInterface $manager): Response
    {

        //check if user is connected
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //get user information
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $repository = $this->getDoctrine()->getRepository(Profil::class);
        $repositoryUsers = $this->getDoctrine()->getRepository(User::class);
        $repositoryAllergene = $this->getDoctrine()->getRepository(Allergene::class);

        dump($requete);

        if ($requete->request->count() > 0) {
            $scanInfo = $requete->request->get('profile_group');
            //cas creer nouveau profile
            if (array_key_exists("submit", $scanInfo)) {
                $profiles = $scanInfo['profils'];
                foreach ($profiles as $key => $value) {
                }
            }
        }
        $profiles = $repository->findby(['user' => $user]);

        $form = $this->createForm(ProfileGroupType::class, $user);



        return $this->render("user/scanner.html.twig", [

            'profils' => $profiles,
            'forms' => $form->createView()
            //   'allergene'=>$allergene
        ]);
    }




     
    /**
     * BarcodeDemoPage1
     * 
     * @Route("/demo/{barcodeNumber}",name="Result")
     * @param  mixed $barcodeNumber
     * @return void twig page 
     */
    public function BarcodeDemoPage1($barcodeNumber)
    {
        //check if barcodeNumber exist
        //voir si barccode entrer correctement 
        if ($barcodeNumber != NULL) {
            dump($barcodeNumber);

            $json1 = file_get_contents('https://fr.openfoodfacts.org/api/v0/product/' . $barcodeNumber);
            $result1 = json_decode($json1);

            return $this->render(
                "site/demo.html.twig",
                ['data' => $result1]
            );
        }
        return $this->render("site/demo.html.twig");
    }












    /**************************************fuunction******************************************/


    
    /**
     * createManageForms
     *
     * @param  mixed $data represent list/collection of users,profiles,scans,allergenes,...
     * @param  mixed $type to recognize the type of form to use
     * @return formsView formsview that can displayed in twig template
     */
    public function createManageForms($data, $type)
    {
        //$data=$repository->findAll();
        //dump($forms1);
        //dump($data);

        $formView = [];
        $i = 0;
        foreach ($data as $key => $value) {
            # code...
            switch ($type) {
                    //casse aadmin and users are the same
                case "admins":
                case "users":

                    $formsView[$i] = $this->createForm(UserBackType::class, $value)->createview();
                    $i = $i + 1;
                    break;

                case "profiles":

                    $formsView[$i] = $this->createForm(ProfileBackType::class, $value)->createview();
                    $i = $i + 1;
                    break;
                case "profilesScan":

                    $formsView[$i] = $this->createForm(ProfileGroupType::class, $value)->createview();
                    $i = $i + 1;
                    break;

                case "allergenes":


                    $formsView[$i] = $this->createForm(AllergeneType::class, $value)->createview();
                    $i = $i + 1;
                    break;

                case "scans":

                    $formsView[$i] = $this->createForm(ScanBackType::class, $value)->createview();
                    $i = $i + 1;
                    break;
                default:
                    return -1; //error interdit
            }
        }
        return $formsView;
    }



    //TODO write algorithme to check allegen function check(allergene[],ingredient[],traces[],$profile[]) 
    //make a lot of more feauture like using profile
    //encour de developement
    //function check allergene
    public function checkAllergene($allergene, $ingredients)
    {
        $status = 0;
        for ($i = 0; $i < count($ingredients); $i++) {
            for ($j = 0; $j < count($allergene); $j++) {
                if (strpos($ingredients[$i], $allergene[$j]) !== false) {
                    $status = $status + 1;
                }
            }
        }
        if ($status == 0) {
            return 0; //false this product is safe
        } else {
            return 1; //true allergene exist in this product
        }
    }
}
