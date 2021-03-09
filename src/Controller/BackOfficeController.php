<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/backOffice/users",name="back_office_user")
     */
    public function backOfficeUser(): Response
    {
        return $this->render('back_office/admin_user.html.twig', [
            'controller_name' => 'BackOfficeController',
        ]);
    }


}
