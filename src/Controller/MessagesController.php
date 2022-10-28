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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @isGranted("ROLE_USER")
 * 
 */
class MessagesController extends AbstractController
{
    /**
     * @Route("/mailbox", name="app_mailbox")
     * 
     * Route de la boite de réception
     */
    public function mailBox(ManagerRegistry $doctrine): Response
    {

        $user = $doctrine->getRepository(PrivateMessage::class)->findAll();
        $received = $doctrine->getRepository(PrivateMessage::class)->findBy(["recipient" => $this->getUser()], ['creationDate' => 'DESC']);

        return $this->render('messages/mailbox.html.twig', [

            'received' => $received,

            'user' => $user,

        ]);
    }

    /**
     * @Route("/outbox", name="app_outbox")
     * 
     * Route de la boite d'envoie'
     */
    public function outBox(ManagerRegistry $doctrine): Response
    {
        $sender = $doctrine->getRepository(PrivateMessage::class)->findBy(["sender" => $this->getUser()], ['creationDate' => 'DESC']);
        return $this->render('messages/outbox.html.twig', [
            'sender' => $sender,
        ]);
    }

    /**
     * @Route("/mailbox/message/{id}", name="app_read")
     * 
     * Route pour lire le message
     */
    public function read(PrivateMessage $message, ManagerRegistry $doctrine, Request $request): Response
    {

        $user = $this -> getUser();

        if($message -> getRecipient() -> getId() == $user -> getId()){
    
            // Etant donnée que le message est ouvert on initialise sont statut à true
            $message -> setIsRead(true);
    
            $entityManager = $doctrine -> getManager();
    
            $entityManager -> persist($message);
    
            $entityManager -> flush(); 
    
            return $this->render('messages/read.html.twig', compact('message'));
            
        } else {
            return $this->redirectToRoute('app_mailbox');
        }

    }


    /**
     * @Route("/envoyer", name="app_send")
     * 
     * Route pour envoyer un message
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
     * @Route("/mailbox/delete/{id}", name="delete")
     */
    public function delete(ManagerRegistry $doctrine, PrivateMessage $message): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($message);
        $entityManager->flush();
        $this->addFlash("message", "Message supprimé.");

        return $this->redirectToRoute("app_mailbox");
    }
}
