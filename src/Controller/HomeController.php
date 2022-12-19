<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [

        ]);
    }

    /**
     * @Route("/condition-generale-utilisation", name="app_cgu")
     */
    public function cgu(): Response
    {
        return $this->render('home/cgu.html.twig', [
            'titre' => ' - Condition générale d\'utilisation',
        ]);
    }
}
