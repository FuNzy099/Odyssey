<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Event;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    /**
     * @Route("/showPost/{id}", name="app_post")
     */
    public function index(ManagerRegistry $doctrine): Response
    {

        $events = $doctrine -> getRepository(Event::class) -> findAll();

        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'events' => $events
        ]);
    }

}
