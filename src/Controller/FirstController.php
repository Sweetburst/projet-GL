<?php


namespace App\Controller;


use App\Entity\User;

use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\RegistrationType;

// 672842f16bddc2b838028f7925f13e0274ca6ec3

class FirstController extends AbstractController
{
    
    // /**
    //  * @Route("/connect",name="connection")
    //  */
    // public function connexion(Request $requete){
    //     dump($requete);
    //     $repo=$this->getDoctrine()->getRepository(User::class);

    //     if($requete->request->count()>0){ //on peut mettre 3 au lieu de 0 //inutile il envoie vide//faut faire de validation avant le submit.
    //         //on a eentrer des parametre nom utilisateur et mot de passe
    //         $username=$requete->request->get('username');
    //         $pass=$requete->request->get('password');
    //         $user=$repo->findByNom($username);
    //         if(sizeof($user)>0)//assure que on a trouver utilisateur
    //             if($pass==$user[0]->getPassword()){
    //              //   mot de pass correct code de redirection a la page d'acceuil en status connecter
    //             }
    //         else{
    //             //nom utilisateur n'existe pas pas de compte pour ce mail ou nom utilisateur
    //         }

    //     }

    //     return $this->render("site/connexion.html.twig");
    // }


    // /**
    //  * @Route("/crCompte",name="creerCompte")
    //  */
    // public function creeCompte(Request $requete,EntityManagerInterface $manager){
    //     dump($requete);
    //     if($requete->request->count()>0){
    //         $user=new User();
    //         $user->setEmail($requete->request->get('email'))
    //              ->setNom($requete->request->get('nom'))
    //              ->setPrenom($requete->request->get('prenom'))
    //              ->setNumeroTelephone($requete->request->get('telephone'))
    //              ->setPassword($requete->request->get('password_1'))
    //              ->setCreatedAt(new \DateTime());

    //         $manager->persist($user);
    //         $manager->flush();
    //     }


    //     return $this->render("site/creerCompte.html.twig");
    // }

    /**
     * @Route("/demo",name="demoPage")
     */
    public function BarcodeDemoPage(Request $requete){
        
        //TODO "this if is uselsess to delete later"

        if($requete->request->count()>0){
            dump($requete);
            
            $json1 = file_get_contents('https://fr.openfoodfacts.org/api/v0/product/'.'Variable recue du requete');
            $json = file_get_contents('https://fr.openfoodfacts.org/api/v0/product/3173990026484');
            $result = json_decode($json);
            $result1 = json_decode($json1);
            $ingredients=$result1->{'product'}->{'ingredients_hierarchy'};
           
            
        }
        //recuperation des information du produit
        $json = file_get_contents('https://fr.openfoodfacts.org/api/v0/product/3173990026484');//biscuit
        //$json = file_get_contents('https://fr.openfoodfacts.org/api/v0/product/3347761000786');//banane cavendish
        $result = json_decode($json);
        dump($result);
        //obtenir la liste des ingredients
        $ingredients=$result->{'product'}->{'ingredients_hierarchy'};
        //dump(count($ingredients));//debug comand
        if(count($ingredients)>0){
            //delete useless information about ingredient like en that mean english and keeo only ingredient name    
            $ingredients1=[];
            for($i=0;$i<count($ingredients);$i++){
            $in=explode(":",$ingredients[$i]);
            $ingredients1[$i]=$in[1];
           // $allergene=$this->checkAllergene("milk",$ingredients1);
            }
        }else{
        //    $allergene="Desoler Pas d'information sur le produit pour l'analyser";
        }
        //ingredients 1 have ingredient name without useless information 
        //check allergene 
      
        return $this->render("site/demo.html.twig",[
            'data' => $result
         //   'allergene'=>$allergene
        ]);

    }


    //TODO write algorithme to check allegen function check(allergene[],ingredient[],traces[]) make a lot of more feauture like using profile
    //function check allergene
    public function checkAllergene($allergene,$ingredients){
        $status=0;
        for($i=0;$i<count($ingredients);$i++){
            for($j=0;$j<count($allergene);$j++){
                if (strpos($ingredients[$i], $allergene[$j]) !== false){
                    $status=$status+1;
                }
            }
        }
        if($status==0){
            return 0;//false this product is safe
        }else{
            return 1;//true allergene exist in this product
        }

    }

       /**
     * @Route("/demo/{barcodeNumber}",name="demoPage1")
     */
    public function BarcodeDemoPage1($barcodeNumber){
       //check if barcodeNumber exist
       //voir si barccode entrer correctement 
        if($barcodeNumber!=NULL){
            dump($barcodeNumber);
            
            $json1 = file_get_contents('https://fr.openfoodfacts.org/api/v0/product/'.$barcodeNumber);
            $result1 = json_decode($json1);
           
            return $this->render("site/demo.html.twig", 
            [ 'data' => $result1]);    
        }
        
        return $this->render("site/demo.html.twig", 
            [ 'data' => $result1]);

    }








    




}