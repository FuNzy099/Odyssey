<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Event;
use App\Data\SearchData;
use App\Form\SearchType;
use App\Form\AddEventType;
use App\Form\EditEventType;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{

    // --------------------- FUNCTION QUI PERMET D'AFFICHER LA LISTE DES EVENEMENTS ET DE FILTRER

    /**
     * @Route("/events", name="list_events")
     * 
     * ManagerRegistry => Nous permet d'utiliser $doctrine qui est une couche d'abstration qui permet de communiquer avec la base de donnée
     */
    public function index(EventRepository $repository, Request $request): Response
    {

        // On instancie un nouveau object SearchDataformulaire
        $data = new SearchData;

        /*
           Permet de définir au niveau de data la page dans la liste des évènements (pagination du bundle snk_paginator),
           Pour ce faire on récupère dans la request le numéro de la page dans l'url, 
           si la valeur de page n'est pas definit on lui attribut 1 par defaut
        */ 
        $data -> page = $request -> get('page', 1);

        /*
            On déclare $form dans le but de créer notre formulaire à l'aide de la methode createForm()

            1er argument : On créer le formulaire à l'aide de la classe SearchType dans le namespace Form
            2eme argument : Permet d'hydrater notre objet $data avec les données saisi dans le formulaire

            Definition hydrater : C'est un terme précis pour dire que le formulaire 
                                  vas remplir les attributs de l'objet avec les valeurs entrées pas l'uilisateur
        */
        $form = $this -> createForm(SearchType::class, $data);

        // handleRequest permet de récupèrer et d'analyser les données saisis dans le formulaire
        $form -> handleRequest($request);
  
        /*
            La méthode findSearchActualEvents() ce trouve dans le repository de Event,
            il permettra de récupérer les évènements global ou en lien avec une recherche, 
            pour ce faire on lui injecte en paramètre $data qui represente les données d'une recherche
        */
        $ActualEvents = $repository -> findSearchActualEvents($data);

        return $this->render('event/index.html.twig', [
            'form' => $form -> createView(),    // Permet de créer le formulaire dans la vue
            'actualEvents' => $ActualEvents,    // Permet de récuperer l'enssemble des évènements (future évènement)
        ]);
    }



    // --------------------- FUNCTION QUI PERMET DE CREER UN EVENEMENT

    /**
     * @Route("/event/add", name="add_event")
     */
    // ManagerRegistry => Nous permet d'utiliser $doctrine qui est une couche d'abstration qui permet de communiquer avec la base de donnée
    public function add(ManagerRegistry $doctrine, Event $event = null, Request $request): Response
    {

        // Instanciation de Event (processus de création d'un objet depuis la class Event)
        $event = new Event();

        // Permet d'initaliser le créateur de l'évènement comme étant l'utlisateur étant en session
        $event->setUserCreator($this->getUser());

        // la function createForm() => permet de construire un formulaire qui ce repose dur le builder de AddEventForm qui lui même ce repose sur l'entity Event
        $form = $this->createForm(AddEventType::class, $event);

        /*
            La function handleRequest permet de récupérer et analyser les données saises dans le formulaire en méthode POST,
            c'est en gros le sasse entre la saisi du formulaire et l'envoi dans la base de donnée.
        */ 
        $form->handleRequest($request);

        /*
            $form -> isSubmitted() => C'est l'équivalent de isset($_POST["submit"]), en gros, le formaulaire est-il validé ?!
            $form -> isValid() => C'est l'équivalent du filter_input, il permet de verifier l'integrité des données saisient dans le formulaire
        */
        if ($form->isSubmitted() && $form->isValid()) {

            /*
                On passe par un manager pour récupérer depuis l'objet $doctrine notre getManager(),
                c'est grace à cette méthode qu'on à accès à persist() et flush()
            */ 
            $entityManager = $doctrine->getManager();

            // On persist notre objet $event, c'est l'équivalent de notre prepare() en PDO
            $entityManager->persist($event);

            // On flush, c'est l'équivalent de notre execute() en PDO, c'est dans cette étape flush() que notre insert into ce fait
            $entityManager->flush();

            $this -> addFlash('message', 'Votre évènement a bien été crée !');

            // Redirection vers la list des évènements 
            return $this->redirectToRoute('list_events');
        }

        // Permet d'afficher le formulaire
        return $this->render('event/add.html.twig', [
            'formAddEvent' => $form->createView(),
        ]);
    }



    // --------------------- FUNCTION QUI PERMET DE MODIFIER UN EVENEMENT

    /**
     * @Route("/event/{id}/edit", name="edit_event")
     */
    // ManagerRegistry => Nous permet d'utiliser $doctrine qui est une couche d'abstration qui permet de communiquer avec la base de donnée
    public function edit(ManagerRegistry $doctrine, Event $event = null, Request $request): Response
    {

        // Si il y a un utilisateur en session et que cet utilisateur correspond à l'userCreator
        if ($this -> getUser() && $this -> getUser() == $event -> getUserCreator() ) {

            // la function createForm() => permet de construire un formulaire qui ce repose dur le builder de AddEventType qui lui même ce repose sur l'entity Event
            $form = $this->createForm(EditEventType::class, $event);

            // la function handleRequest permet de récupérer et analyser les données saises dans le formulaire en méthode POST, c'est en gros le sasse entre la saisi du formulaire et l'envoi dans la base de donné
            $form->handleRequest($request);

            // $form -> isSubmitted() => C'est l'équivalent de isset($_POST["submit"]), en gros, le formaulaire est-il validé ?!
            // $form -> isValid() => C'est l'équivalent du filter_input, en gros, il permet de verifier l'integrité des données saisient dans le formulaire
            if ($form->isSubmitted() && $form->isValid()) {

                // On passe par un manager pour récupérer depuis l'objet $doctrine notre getManager(), c'est grace à cette méthode qu'on à accès à persist() et flush()
                $entityManager = $doctrine->getManager();

                // On persist notre objet $event, c'est l'équivalent de notre prepare() en PDO
                $entityManager->persist($event);

                // On flush, c'est l'équivalent de notre execute() en PDO, c'est dans cette étape flush() que notre insert into ce fait
                $entityManager->flush();

                // Redirection vers la list des évènements 
                return $this->redirectToRoute('list_events');
            }
        } else {
            return $this->redirectToRoute('app_login');
        }

        // Permet d'afficher le formulaire
        return $this->render('event/edit.html.twig', [
            'formEditEvent' => $form->createView(),
        ]);
    }



    // --------------------- FUNCTION QUI PERMET DE SUPPRIMER UN EVENEMENT

    /**
     * @Route("/event/{id}/delete", name="delete_event")
     */
    // ManagerRegistry => Nous permet d'utiliser $doctrine qui est une couche d'abstration qui permet de communiquer avec la base de donnée
    public function delete(ManagerRegistry $doctrine, Event $event = null): Response
    {
        if($this -> getUser() && $this -> getUser()  == $event -> getUserCreator()){

            // On passe par un manager pour récupérer depuis l'objet $doctrine notre getManager(), c'est grace à cette méthode qu'on à accès à remove() et flush()
            $entityManager = $doctrine->getManager();
    
            // On premove notre objet $event
            $entityManager->remove($event);
    
            // On flush, c'est l'équivalent de notre execute() en PDO, c'est dans cette étape flush() que notre insert into ce fait
            $entityManager->flush();
    
            // Redirection vers la list des évènements 
            return $this->redirectToRoute('list_events');

        } else {

            return $this -> redirectToRoute('app_home');

        }
    }


    

}
