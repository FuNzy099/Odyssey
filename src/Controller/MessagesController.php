<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\PrivateMessage;
use App\Form\PrivateMessageType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Notifier\Recipient\Recipient;

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
     * @Route("/envoyer/{id}", name="app_send")
     * 
     * Route pour envoyer un message
     */
    public function send(Request $request, ManagerRegistry $doctrine, User $user): Response
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
            
            $message -> setRecipient($user);

            $entityManager = $doctrine -> getManager();

            $entityManager -> persist($message);

            $entityManager -> flush();

            $this -> addFlash("message", "Message envoyé avec succès ! ");

            return $this -> redirectToRoute("show_history", ['id' => $this -> getUser() -> getId()]);
        }

        return $this -> render("messages/send.html.twig",[

            "formMessage" => $form -> createView(),
            'user' => $user,

        ]);
        
    }

    /**
     * @Route("/reponse/{id}", name="app_reponse")
     * 
     * Route pour envoyer un message
     */
    public function reponse(User $user, ManagerRegistry $doctrine, Request $request): Response
    {

        // On instancie un nouveau objet de PrivateMessage
        $message = new PrivateMessage;

        // On créer un formulaire qui ce base sur le builder de PrivateMessageType
        $form = $this -> createForm(PrivateMessageType::class, $message);

        // Permet de récupèrer le formulaire dans la requette dans le but de le traiter
        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){
            
            $message -> setSender($this -> getUser());
 
            $message -> setRecipient($user);

            $entityManager = $doctrine -> getManager();

            $entityManager -> persist($message);

            $entityManager -> flush();

            $this -> addFlash("message", "Message envoyé avec succès ! ");

            return $this -> redirectToRoute("app_outbox", ['id' => $this -> getUser() -> getId()]);
        }




        return $this -> render("messages/send.html.twig",[
            "formMessage" => $form -> createView(),
            'user' => $user,
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
