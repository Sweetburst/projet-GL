<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Profil;
use App\Entity\Allergene;
use App\Form\UserBackType;
use App\Form\AllergeneType;
use App\Form\ProfileBackType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BackOfficeController extends AbstractController
{

    /**
     * index1
     * Page initial de l'admin 
     * 
     * Route :/backOffice/test
     * 
     * 
     * name of Route: back_office_test; 
     * 
     * @Route("/backOffice/test",name="back_office_test")
     * @return Response
     */
    public function index1(): Response
    {
        return $this->render('back_office/test.html.twig');
    }

    /**
     * backOfficeUser
     * page admin pour gerer les utilisateur ou page pour gerer les admin
     * 
     * Route :/backOffice/{types} ; types is a variable that can be users or admins
     * 
     * name of Route: back_office_user_or_admins;
     * 
     * @Route("/backOffice/{types}",name="back_office_user_or_admins")
     * @param  mixed $requete pour recuperent les donne du site
     * @param  mixed $manager pour gerer nos entite avec la base de donne
     * @param  mixed $encoder Bcrypt encoder pour les mot de passe
     * @param  mixed $types pour definir si on recupere liste utilisateur normal or admins
     * @return Response
     *  -formRegistration form pour enregistrer soit un nouveau utilisateur ou admin
     *  -forms contenant une liste de forms qui represente tous les utilissateur ou admins
     *  -types pour decider si il va modifier les utilisateur ou els admins
     */
    public function backOfficeUser(Request $requete, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, String $types): Response
    {

        if ($types != 'admins' or $types != 'users') {
            // return $this->render('error_page.html.twig');
        }
        $repository = $this->getDoctrine()->getRepository(User::class);
        if ($types == 'users') {
            $users = $repository->findBy(['admin' => false]);
        } else {
            $users = $repository->findBy(['admin' => true]);
        }

        if ($requete->request->count() > 0) {
            $userInfo = $requete->request->get('user_back');
            dump($userInfo);
            $userTomodifieOrDelete = $repository->find($requete->request->get('id'));
            if (array_key_exists("modifier", $userInfo)) {
                $userTomodifieOrDelete->setNom($userInfo['nom']);
                $userTomodifieOrDelete->setPrenom($userInfo['prenom']);
                $userTomodifieOrDelete->setEmail($userInfo['email']);
                $userTomodifieOrDelete->setNumeroTelephone($userInfo['numeroTelephone']);
                $manager->persist($userTomodifieOrDelete);
                $manager->flush();
            } elseif (array_key_exists("supprimer", $userInfo)) {
                $manager->remove($userTomodifieOrDelete);
                $manager->flush();
            }
        }

        //$usersOnly=$repository->findBy(['admin'=> false]);
        $formsView = $this->createManageForms($users, $types);
        //dump($this->createManageForms($users,$types,$manager,$requete));

        //geration du formulaire doit etre obligatoirement dans le controller
        //et n'ont pas dans une function separer pour pouvoir recuperer nouveau data directement

        $user = new User(); //utilisateur vide
        //obtenir form pour creer nouveau compte
        $formRegistration = $this->createForm(RegistrationType::class, $user);
        //different des autre car form de type different que celui utiliser pour l'affichage
        $formRegistration->handleRequest($requete);
        if ($formRegistration->isSubmitted() && $formRegistration->isValid()) {
            dump($requete);
            if (!$user->getId()) {
                // Si utilisateur pas inscrit on lui attribut une date d'inscription
                $user->setCreatedAt(new \DateTime());
            }

            $hash = $encoder->encodePassword($user, $user->getPassword()); //hashage du mdp avec l'algo bcrypt CF fichier Config->Packages->security.yaml
            $user->setPassword($hash);
            //decision si le creer en temp que admin ou uttilisation selon le type
            if ($types == 'users') {
                $user->setAdmin(false);
            } else {
                $user->setAdmin(true);
            }
            $manager->persist($user);
            $manager->flush();
            //redirection sur la meme page avec nouveau utilisateur
            //return $this->redirectToRoute("back_office_user_or_admins",['types'=>$types]);
        }

        //reendering to the page
        return $this->render('back_office/admin_user.html.twig', [
            //'users'=>$users,
            'forms' => $formsView,
            'formRegistration' => $formRegistration->createView(),
            'type' => $types
        ]);
    }





    /**
     * backOfficeProfiles
     * 
     * page admin pour gerer les profiles
     * 
     * Route :/back_Office/profiles
     * 
     * name of Route:back_office_profile;
     * 
     * @Route("/back_Office/profiles",name="back_office_profile")
     * 
     * @param  mixed $requete get request from page when submiting,...
     * @param  mixed $manager entity manager to work with database
     * @return Response return the page to show
     *  -forms contenant liste de orm de type profileBackType qui represente tout les profile
     *  -formRegistration pour creer le form dans le modal qui permet de creer le form de creation d'un nouveau profile
     */

    public function backOfficeProfiles(Request $requete, EntityManagerInterface $manager): Response
    {
        $repository = $this->getDoctrine()->getRepository(Profil::class);
        $repositoryUsers = $this->getDoctrine()->getRepository(User::class);
        $repositoryAllergene = $this->getDoctrine()->getRepository(Allergene::class);
        if ($requete->request->count() > 0) {
            $ProfileInfo = $requete->request->get('profile_back');
            //cas creation profile
            if (array_key_exists("creeProfile", $ProfileInfo)) {
                $newProfile = new Profil();
                $newProfile->setCreatedAt(new \DateTime())
                    ->setUser($repositoryUsers->find($ProfileInfo['user']))
                    ->setNom($ProfileInfo['nom'])
                    ->setPrenom($ProfileInfo['prenom'])
                    ->setAge($ProfileInfo['age']);
                //pour remplire les allergene puisque il pet etre multiple
                if (array_key_exists("allergenes", $ProfileInfo)) {
                    $tabAllergene = $ProfileInfo['allergenes'];
                    dump($tabAllergene);
                    //add the allergene selectioner to the profile
                    for ($i = 0; $i < count($tabAllergene); $i++) {
                        $newProfile->addAllergene($repositoryAllergene->find($tabAllergene[$i]));
                    }
                }
                //l'inserer dans la base de donne 
                $manager->persist($newProfile);
                $manager->flush();
            }
            //cas modifier
            if (array_key_exists("modifier", $ProfileInfo)) {
                $ProfileTomodifieOrDelete = $repository->find($requete->request->get('id'));
                $ProfileTomodifieOrDelete->setNom($ProfileInfo['nom']);
                $ProfileTomodifieOrDelete->setPrenom($ProfileInfo['prenom']);
                $ProfileTomodifieOrDelete->setAge($ProfileInfo['age']);
                $ProfileTomodifieOrDelete->setAge($ProfileInfo['user']);
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
                //modifier dans la base de donne
                $manager->persist($ProfileTomodifieOrDelete);
                $manager->flush();

                //cas supprimer

            } elseif (array_key_exists("supprimer", $ProfileInfo)) {
                $ProfileTomodifieOrDelete = $repository->find($requete->request->get('id'));
                $manager->remove($ProfileTomodifieOrDelete); //delete profile selectioned
                $manager->flush(); //delete from  database
            }
        }
        //apres la requete pour pouvoir obtenir les modification qui ont eu lieu sur la base de donne
        $profiles = $repository->findall(); //get all profile

        //get les form pour les affichage de donner dans le tableau
        $formsView = $this->createManageForms($profiles, "profiles");
        //form de creation nouveau profile
        $prof = new Profil(); //profil vide
        ///form pour la creation d'un nouveau utilisateur
        $formRegistration = $this->createForm(ProfileBackType::class, $prof);
        $formRegistration->handleRequest($requete);
        //rendering the page
        return $this->render('back_office/profil_back_office.html.twig', [
            'forms' => $formsView,
            'formRegistration' => $formRegistration->createView()
        ]);
    }





    /**
     * backOfficeAllergene
     * 
     * page admin pour gerer les Allergene
     * 
     * Route :/back_Office/allergenes
     * 
     * name of Route:back_office_allergene;
     * @Route("/back_Office/allergenes",name="back_office_allergene")
     * 
     * @param  mixed $requete get request from page when submiting,...
     * @param  mixed $manager entity manager to work with database
     * @return Response return the page to show
     */

    public function backOfficeAllergene(Request $requete, EntityManagerInterface $manager): Response
    {
        //repository that will contain our allergene from database
        $repository = $this->getDoctrine()->getRepository(Allergene::class);
        //dump($requete);//debug
        if ($requete->request->count() > 0) {
            // dump($requete)//debug 
            $AllergeneInfo = $requete->request->get('allergene');
            //cas creer nouveau allergene dans la base de donne
            if (array_key_exists("creeNewAllergene", $AllergeneInfo)) {
                $newAllergene = new Allergene();
                $newAllergene->setCreatedAt(new \DateTime())
                    ->setNomAllergene($AllergeneInfo['nom_allergene'])
                    ->setDescription($AllergeneInfo['description']);
                $manager->persist($newAllergene);
                $manager->flush();
            }
            //cas modifier un allergene par exmple sa description ou nom
            if (array_key_exists("modifier", $AllergeneInfo)) {
                $AllergeneTomodifieOrDelete = $repository->find($requete->request->get('id'));
                $AllergeneTomodifieOrDelete->setNomAllergene($AllergeneInfo['nom_allergene']);
                $AllergeneTomodifieOrDelete->setDescription($AllergeneInfo['desscription']);
                $AllergeneTomodifieOrDelete->setCreatedAt($AllergeneInfo['createdAt']);
                //  $ProfileTomodifieOrDelete->setNumeroTelephone($ProfileInfo['numeroTelephone']);
                $manager->persist($AllergeneTomodifieOrDelete);
                $manager->flush();
                //cas supprimer l'allergene de la base de donne donc de l'appli plutot
            } elseif (array_key_exists("supprimer", $AllergeneInfo)) {
                $AllergeneTomodifieOrDelete = $repository->find($requete->request->get('id'));
                $manager->remove($AllergeneTomodifieOrDelete);
                $manager->flush();
            }
        }
        //recuperer tout les allergene 
        $allergenes = $repository->findall();

        //get les form pour les affichage de donner dans le tableau
        $formsView = $this->createManageForms($allergenes, "allergenes");
        //form de creation nouveau profile
        $allerg = new Allergene(); //profil vide
        //dump($allerg);//debug
        $formRegistration = $this->createForm(AllergeneType::class, $allerg);
        //rendering the page
        return $this->render('back_office/Allergene_back_office.html.twig', [
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
}
