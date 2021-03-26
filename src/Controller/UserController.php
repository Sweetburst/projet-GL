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

/**
 * UserController
 * ce controller est fait pour gerer tout les page lier au utilisateur
 */
class UserController extends AbstractController
{

    /**
     * index
     * page acceuil de l'utilisateur d'ou il poura naviguer 
     * different fonctionalite du site
     * 
     * Route :/user
     * 
     * name of Route:user;to acces it from twig {{path('user')}}
     * @Route("/user", name="user")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }





    /**
     * profile
     * page permettant de gerer les different profile dans un compte 
     * Route :/user/profiles 
     * 
     * name of Route:user_profiles
     * 
     * $ProfileInfo contient information du form submitter avec le type button utiliser soit creer,supprimer,modifier
     * 
     * $formsView conteant liste des form de type profileBackType ou chauque form represente un profile qui peut etre inserer dans notre fichier Twig
     * @Route("/user/profiles", name="user_profiles")
     * @param  mixed $requete
     * @param  mixed $manager
     * @return Response 
     *  -forms liste of forms for each profile
     *  -formRegistration for a new profile
     */
    public function profile(Request $requete, EntityManagerInterface $manager): Response
    {
        //check if user is connected if not go automaticly to connexion page
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var \App\Entity\User $user */
        // returns your User object, or null if the user is not authenticated
        $user = $this->getUser();
        //obtenir nos repository relier a notre base de donne avec chaque type d'entite
        $repository = $this->getDoctrine()->getRepository(Profil::class);
        $repositoryUsers = $this->getDoctrine()->getRepository(User::class);
        $repositoryAllergene = $this->getDoctrine()->getRepository(Allergene::class);
        //dump($requete);//pour Debug
        if ($requete->request->count() > 0) {
            //obtenir les information du profile a modifier,supprimer,creer 
            $ProfileInfo = $requete->request->get('profile_back');

            //verifier dans qu'elle cas on est sup,mod,creer
            //cas creer nouveau profile
            if (array_key_exists("creeProfile", $ProfileInfo)) {
                $newProfile = new Profil(); //initiliser profile vide
                //ajouter les information recuperer du form  au nouveau entity profile
                $newProfile->setCreatedAt(new \DateTime()) //ajouter date de creation au moment le button appuyer
                    ->setUser($repositoryUsers->find($user)) //utilise reposuser pour trouver a quel user le relier
                    ->setNom($ProfileInfo['nom'])
                    ->setPrenom($ProfileInfo['prenom'])
                    ->setAge($ProfileInfo['age']);
                //possibilite d'avoir allergene ou non   
                if (array_key_exists("allergenes", $ProfileInfo)) {
                    //taballergene car possibbilite de plusieur allergene
                    $tabAllergene = $ProfileInfo['allergenes'];
                    //dump($tabAllergene);//debug
                    //add the allergene selectioner to the profile
                    for ($i = 0; $i < count($tabAllergene); $i++) {
                        $newProfile->addAllergene($repositoryAllergene->find($tabAllergene[$i]));
                    }
                }
                //ajouter le nouveau profil au manager et l'insere dans la base de donne
                $manager->persist($newProfile);
                $manager->flush();
            }

            //cas modifier

            if (array_key_exists("modifier", $ProfileInfo)) {
                $ProfileTomodifieOrDelete = $repository->find($requete->request->get('id')); //recuperer le profile a modifier

                $ProfileTomodifieOrDelete->setNom($ProfileInfo['nom']);
                $ProfileTomodifieOrDelete->setPrenom($ProfileInfo['prenom']);
                //$ProfileTomodifieOrDelete->setAge($ProfileInfo['age']);//age non definie dans le form pour l'instant
                $ProfileTomodifieOrDelete->setUser($user);

                if (array_key_exists("allergenes", $ProfileInfo)) {
                    $tabAllergene = $ProfileInfo['allergenes'];
                    dump($tabAllergene);
                    //get all allergenes in database
                    $allergenes = $repositoryAllergene->findall();
                    //remove all previeus allergene if we dont do it when removing the selectioned allergene will not be deleted from profiles
                    foreach ($allergenes as $key => $value) {
                        $ProfileTomodifieOrDelete->removeAllergene($value);
                    }
                    //add the allergene selectioner 
                    for ($i = 0; $i < count($tabAllergene); $i++) {
                        $ProfileTomodifieOrDelete->addAllergene($repositoryAllergene->find($tabAllergene[$i]));
                    }
                }
                //modifier profil dans le manager et l'insere dans la base de donne
                $manager->persist($ProfileTomodifieOrDelete);
                $manager->flush();

                // cas supprimer

            } elseif (array_key_exists("supprimer", $ProfileInfo)) {
                $ProfileTomodifieOrDelete = $repository->find($requete->request->get('id')); //recuperer le profile a supprimer
                $manager->remove($ProfileTomodifieOrDelete); //delete profile selectioned
                $manager->flush(); //delete from  database
            }
        }

        //recuperer les profiles apres la requete pour obtenir les modification realiser
        $profiles = $repository->findby(['user' => $user]);
        //recuper les forms a fficher de chque profile
        $formsView = $this->createManageForms($profiles, "profiles");

        //initaliser le nouveau prof au formRegistration qui permet de creer nouveau profile
        $prof = new Profil(); //profil vide
        //creer formRegistration pour le modal qui permet de creer novueau profil
        $formRegistration = $this->createForm(ProfileBackType::class, $prof);
        $formRegistration->handleRequest($requete);
        $user = $this->getUser();
        //dump($profiles);//debug
        return $this->render("user/profileGerer.html.twig", [
            'forms' => $formsView,
            'formRegistration' => $formRegistration->createView()
        ]);
    }





    /**
     * editInformation
     * page pour gerer information du compte comme changer mot de passe modifier information personnelle
     * 
     * Route :/user/account
     *  
     * name of Route:user_account
     * @Route("/user/account", name="user_account")
     * @param  mixed $requete
     * @param  mixed $manager
     * @param  mixed $encoder
     * @return Response
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
     * creerProfile
     * @Route("/user/creerProfile",name="creer_profile")
     * @Route("/user/{id}/edit",name="edit_profile")
     * ancien version pour gerer les profil qui permeterr de crerr un profile ou le gerer dans la meme page twig selon le mode envoyer
     * 
     * mode est id du profile si il existe donc gerer le profile selectioner sinon cree un nouveau
     * @param  mixed $profile
     * @param  mixed $requete
     * @param  mixed $manager
     * @return Response
     */
    public function creerProfile(Profil $profile = null, Request $requete, EntityManagerInterface $manager): Response
    {
        //create empty profile 
        if (!$profile) {
            $profile = new Profil();
        }

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




    /**************************************Page en cour de developement********************************************/




    /**
     * scanner
     * page pour scanner les produit
     * 
     * Route :/user/scanner
     * 
     * name of Route:user_scan
     * 
     * Proposition:ajouter un input permettant de entrer le barcode manuelement 
     * et verifier si il respecte le format d'un barcode exemple 13 chiffre  
     * et type Ean13 utiliser pour les produit alimentaire
     * @Route("/user/scanner",name="user_scan")
     * 
     * @return Response toShow the twig page with the sended Data
     *  -page Twig: user/scanner.html.twig
     *  -profils contains list of profiles related to user
     *  -forms contains list of forms related to profiles 
     */
    public function scanner(Request $requete, EntityManagerInterface $manager): Response
    {

        //check if user is connected
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //get user information
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        //initialisation des differente repository conteanant information de la base de donne
        $repository = $this->getDoctrine()->getRepository(Profil::class);
        $repositoryUsers = $this->getDoctrine()->getRepository(User::class);
        $repositoryAllergene = $this->getDoctrine()->getRepository(Allergene::class);

        //dump($requete);//debug
        //verifier que on a recu parmatre de la request donc form submiter
        if ($requete->request->count() > 0) {
            //obtenir info du form submiter contenant liste des profile a considerer
            $scanInfo = $requete->request->get('profile_group');
            if (array_key_exists("submit", $scanInfo)) {
                $profiles = $scanInfo['profils']; //recuperer la liste des profiles a consider
                foreach ($profiles as $key => $value) {
                    //code pour verifier les allergene pour chaque profiles
                    //coder un return avec des specialite comme nom allergene trouver,traces trouve,des warning,..
                }
            }
        }
        //trouver les profils lier a ce utilisateur
        $profiles = $repository->findby(['user' => $user]);
        //creer le form de type profilGroupType pour afficher les profile
        //BUG :pour l'instant il rend la liste de tout les profile existant dans la bdd
        //SOLUTION: utiliser un custom createQueryBuilder dans la class de profilGroupType en recuperant les profil
        $form = $this->createForm(ProfileGroupType::class, $user);


        return $this->render("user/scanner.html.twig", [
            'profils' => $profiles,
            'forms' => $form->createView()
            //   'allergene'=>$allergene
        ]);
    }





    /**
     * BarcodeDemoPage1
     * page qui va afficher le rsultat apres scannage du barcode
     * 
     *  route:/demo/{barcodnumber}
     * 
     *  name= result
     *  
     *  lebarcodenumber est le numero du barcode qui est envoyer automatiquement apres le scan
     * 
     * @Route("/demo/{barcodeNumber}",name="Result")
     * @param  mixed $barcodeNumber
     * @return void twig page 
     * -page Twig: user/scanner.html.twig
     * -data:contenant les information du produit
     * -allergene:result du scan a ajouter
     *
     */
    public function BarcodeDemoPage1($barcodeNumber)
    {
        //check if barcodeNumber exist
        //TODO voir si barcode entrer correctement  
        if ($barcodeNumber != NULL) {
            //dump($barcodeNumber);//debug

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
     * fonction permetant de creer liste de form de type diiferent selon ses parametre
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




    /************************************function en cour de developement*****************************/




    //TODO write algorithme to check allegen function check(allergene[],ingredient[],traces[],$profile[]) 
    //make a lot of more feauture like using profile
    //encour de developement
    //function check allergene    
    /**
     * checkAllergene
     * function not ready to use
     * @param  mixed $allergene
     * @param  mixed $ingredients
     * 
     * Return 0 or 1 ;
     * 
     * 0:pas d'allergene detecter
     * 
     * 1:allergene detecter dans la liste d'ingredient
     * @return void
     */
    public function checkAllergene($allergene, $ingredients)
    {
        $status = 0; //initial status 0 not allergic other number allergic
        for ($i = 0; $i < count($ingredients); $i++) {
            //loop on list of ingredient
            for ($j = 0; $j < count($allergene); $j++) {
                //loop on list of allergenes 
                //check if allergenes in ingredient
                //use strpos because sometime ingredient can be like milk-powder 
                //then we decide he is allergic if milk initalised as allergene
                if (strpos($ingredients[$i], $allergene[$j]) !== false) {
                    $status = $status + 1;
                }
            }
        }

        //check the result and return response
        if ($status == 0) {
            return 0; //false this product is safe
        } else {
            return 1; //true allergene exist in this product
        }
    }
}
