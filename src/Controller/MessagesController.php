<?php

namespace App\Controller;

use App\Entity\PrivateMessage;
use App\Form\PrivateMessageType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
    /**
     * @Route("/messages", name="app_messages")
     */
    public function index(): Response
    {
        return $this->render('messages/index.html.twig', [
            'controller_name' => 'MessagesController',
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

            // $message -> setRecipient($form->get('recipient')->getData());

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
}
