<?php


namespace App\Controller;

use App\Entity\User;

use App\Form\RegistrationType;
use App\Form\GroupAllergeneType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class FirstController extends AbstractController
{

    /**
     * index
     * Page intermediaire qui decide ou aller apres connexion admin au backoffice,user au user
     * 
     * Route :/intermediaire
     * 
     * name of Route:back_office;
     * 
     * PROPOSITION:changer le nom de la route et la modifier dans les fichier twig 
     * et le fichier config/packages/security_yaml
     * @Route("/intermediaire",name="back_office")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('back_office/index.html.twig');
    }


    /**
     * BarcodeDemoPage
     * page demo pour voir scanner produit et obtenir si le produit contient allergene du lait
     * @Route("/demo",name="demoPage")
     * @param  mixed $requete
     * @return void
     */
    public function BarcodeDemoPage(Request $requete)
    {

        //TODO "this if is uselsess to delete later"

        if ($requete->request->count() > 0) {
            //dump($requete);

            $json1 = file_get_contents('https://fr.openfoodfacts.org/api/v0/product/' . 'Variable recue du requete');
            $json = file_get_contents('https://fr.openfoodfacts.org/api/v0/product/3173990026484');
            $result = json_decode($json);
            $result1 = json_decode($json1);
            $ingredients = $result1->{'product'}->{'ingredients_hierarchy'};
        }

        $form = $this->createForm(GroupAllergeneType::class);
        return $this->render("site/scanner.html.twig", [
            'forms' => $form->createView()
            //   'allergene'=>$allergene
        ]);
    }


    //TODO write algorithme to check allegen function check(allergene[],ingredient[],traces[]) make a lot of more feauture like using profile
    //function check allergene    
    /**
     * checkAllergene
     *
     * 
     * 
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
        $status = 0;
        for ($i = 0; $i < count($ingredients); $i++) {
            if (strpos($ingredients[$i], $allergene) !== false) {
                $status = $status + 1;
            }
        }
        if ($status == 0) {
            return 0; //false this product is safe
        } else {
            return 1; //true allergene exist in this product
        }
    }


    /**
     * BarcodeDemoPage1
     * page affiche le resultat du barcode scanner
     *  
     * Route :/demo/{barcodeNumber}; barcodeNumber represente le code barre du produit
     * 
     * name of Route:demoPage1;
     * @Route("/demo/{barcodeNumber}/",name="demoPage1")
     * @param  mixed $barcodeNumber
     * @return void
     */
    public function BarcodeDemoPage1($barcodeNumber)
    {
        //check if barcodeNumber exist
        //voir si barccode entrer correctement
        if ($barcodeNumber != null) {
            dump($barcodeNumber);
            $json1 = file_get_contents('https://fr.openfoodfacts.org/api/v0/product/' . $barcodeNumber);
            $result = json_decode($json1);
            dump($result);
            //obtenir la liste des ingredients
            if ($result->{'status'} == 0) {
                return -1;
            }
            $ingredients = $result->{'product'}->{'ingredients_hierarchy'};
            //dump(count($ingredients));//debug comand
            if (count($ingredients) > 0) {
                //delete useless information about ingredient like en that mean english and keeo only ingredient name
                $ingredients1 = [];
                for ($i = 0; $i < count($ingredients); $i++) {
                    $in = explode(":", $ingredients[$i]);
                    $ingredients1[$i] = $in[1];
                    $allergene = $this->checkAllergene("milk", $ingredients1);
                }
            } else {
                $allergene = "Desoler Pas d'information sur le produit pour l'analyser";
            }
            return $this->render(
                "site/demo.html.twig",
                [
                    'data' => $result,
                    'allergene' => $allergene
                ]
            );
        }
        return $this->render("site/demo.html.twig");
    }


    /**
     * Avenir
     * page qui dit au utilsateur que elle est encore en construction 
     * 
     *  Route :/avenir
     * 
     * name of Route:aVenir;
     * @Route("/avenir",name="aVenir")
     * @return void
     */
    public function Avenir()
    {
        return $this->render("site/avenir.html.twig");
    }
}
