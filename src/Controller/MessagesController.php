<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\PrivateMessage;
use App\Form\PrivateMessageType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessagesController extends AbstractController
{
    /**
     * @Route("/messages/{id}", name="app_messages")
     */
    public function index(ManagerRegistry $doctrine, User $users): Response
    {        

        // $messages = $repository -> messageRequette();

        $users = $doctrine -> getRepository(User::class) -> findoneBy(['id' => $this -> getUser()]);
     

        return $this->render('messages/index.html.twig', [

            'controller_name' => 'MessagesController',

            "user" => $users

        ]);
    }

    /**
     * @Route("/envoyer", name="send")
     */
    public function send(Request $request, ManagerRegistry $doctrine): Response
    {
        // On instancie un nouveau objet de PrivateMessage
        $message = new PrivateMessage;

        // On créer un formulaire qui ce base sur le builder de PrivateMessageType
        $form = $this -> createForm(PrivateMessageType::class, $message);

        // Permet de récupèrer le formulaire dans la requette dans le but de le traiter
        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){

            // Permet d'ajouter l'expéditeur du message
            $message -> setSender($this -> getUser());

            $entityManager = $doctrine -> getManager();

            $entityManager -> persist($message);

            $entityManager -> flush();

            $this -> addFlash("message", "Message envoyé avec succès ! ");

            return $this -> redirectToRoute("app_messages");
        }

        return $this -> render("messages/send.html.twig",[

            "formMessage" => $form -> createView()

        ]);
        
    }



    /**
     * @Route("/reception", name="app_received")
     */
    public function received(): Response
    {
        return $this->render('messages/received.html.twig');
    }



    /**
     * @Route("/reception/message/{id}", name="app_read")
     */
    public function read(PrivateMessage $message, ManagerRegistry $doctrine): Response
    {
        // Etant donnée que le message est ouvert on initialise sont statut à true
        $message -> setIsRead(true);

        $entityManager = $doctrine -> getManager();

        $entityManager -> persist($message);

        $entityManager -> flush(); 

        return $this->render('messages/read.html.twig', compact('message'));
    }
}
