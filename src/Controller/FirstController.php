<?php


namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use PHPZxing\PHPZxingDecoder;

class FirstController extends AbstractController
{
    /**
     * @Route("/test",name="test")
     */
    public function test(){

  /* Code barcode zxing not working
      $decoder= new PHPZxingDecoder();
        $decoder->setJavaPath('"D:/Program Files/Java/jdk-12.0.2/bin/java.exe"');
        $decodedData    = $decoder->decode('D:/isen/M2 Bac+5/project/img bar/banane.jpeg');
        print_r($decodedData);
        dump($decodedData);
        $tmpfilePath='D:/isen/M2 Bac+5/project/img bar/banane.jpeg';
        $decoder = new \PHPZxing\PHPZxingDecoder();
        // Set java path with double quote '"path/to/java/exe"'
        $decoder->setJavaPath("C:\Program Files (x86)\Common Files\Oracle\Java\javapath\java.exe" );
        $data = $decoder->decode('D:/isen/M2 Bac+5/project/img bar/banane.jpeg');
        dump($data);*/
        return $this->render("base.html.twig");
    }
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



}