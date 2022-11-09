<?php

namespace App\Controller;


use DateTime;
use App\Entity\User;
use App\Entity\Event;
use App\Data\SearchData;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }



    // --------------------- FUNCTION QUI PERMET DE S'INSCRIRE A UN EVENEMENT

    /**
     * @Route("/event/registration/{id}", name="registration_event")
     */
    // ManagerRegistry => Nous permet d'utiliser $doctrine qui est une couche d'abstration qui permet de communiquer avec la base de donnée
    public function registrationEvent(ManagerRegistry $doctrine, Event $event): Response
    {

        // Si il y a un utilisateur en session
        if($this -> getUser()){

            /*
                Si le nombre de personnes enregistrées à l'évènement est égale au nombre de place max on redirige vers la page d'acceuil ! 

                Ce if est là pour éviter de mettre dans l'url la route d'inscription sur un évènement plein et de ce retrouver avec des valeurs négative..
                (exemple: https://127.0.0.1:8000/event/registration/12)
            */
            if( count($event -> getUsers()) == $event -> getMaxPlaces()){

                return $this -> redirectToRoute('app_home');

            } else {

                // On récupere la méthode addUser() qui ce trouve dans l'entity Event
                $event -> addUser($this -> getUser() );
        
                // On passe par $entityManager pour récupérer depuis l'objet doctrine la methode getManager(), c'est grace à lui qu'on accede à persit() et flush()
                $entityManager = $doctrine -> getManager();
        
                // On persist notre objet $user, c'est l'équivalent de prepare() en PDO
                $entityManager -> persist($event);
        
                // On flush, c'est l'équivalent de execute() en PDO, c'est dans cette étape flush() que notre insert into ce fait
                $entityManager -> flush();
        
                // On fais une redirection
                return $this -> redirectToRoute('list_events');

            }          

        } else {
            return $this -> redirectToRoute('app_login');
        }
     
    }



    // --------------------- FUNCTION QUI PERMET DE SE DESINSCRIRE A UN EVENEMENT

    /**
     * @Route("/event/unsub/{id}", name="unsub_event")
     */
    // ManagerRegistry => Nous permet d'utiliser $doctrine qui est une couche d'abstration qui permet de communiquer avec la base de donnée
    public function unsubEvent(ManagerRegistry $doctrine, Event $event): Response
    {

        // Si il y a un utilisateur en session
        if($this -> getUser()){

            // On récupére la méthode removeUser() qui ce trouve de l'entity Event
            $event -> removeUser($this -> getUser());
    
            // On passe par $entityManager pour récupérer depuis l'objet doctrine la méthode getManager(), c'est grace à lui qu'on accede à persist() et flush()
            $entityManager = $doctrine -> getManager();
    
            // On persist, c'est l'équivalent de prepare() en PDO
            $entityManager -> persist($event);
    
            // On flush, c''est l'équivalent de execute() en PDO, c'est dans cette étape flush() que notre insert into ce fait
            $entityManager -> flush();
    
            // On fais une redirection
            return $this -> redirectToRoute('list_events');

        } else {
            return $this ->  redirectToRoute('app_login');
        }
     
    }


    /**
     * @Route("/events/registration/user/{id}", name="show_registration_list")
     */
    public function registrationList(ManagerRegistry $doctrine, User $users, EventRepository $repository): Response
    {
        $users = $doctrine -> getRepository(User::class) -> findby(['id' => $this -> getUser()], []);

        $now = new DateTime();

        return $this -> render('user/registrationList.html.twig',[

            'users' => $users,

            'now' => $now,
            
        ]);

    }



    /**
     * @Route("/events/organization/user/{id}", name="show_organization_list")
     */
    public function organizationList(ManagerRegistry $doctrine, User $users): Response
    {
        $users = $doctrine -> getRepository(User::class) -> findby(['id' => $this -> getUser()], []);

        $events = $doctrine -> getRepository(Event::class) -> findAll();

        return $this -> render('user/organizationList.html.twig',[

            'users' => $users,

            'events' => $events,

        ]);

    }



    /**
     * @Route("/events/history/user", name="show_history")
     */
    public function history(EventRepository $repository): Response
    {
        $eventsUser = $repository -> pastEvent();

        return $this -> render('user/history.html.twig',[            

            'eventsUser' => $eventsUser
            
        ]);

    }

    /**
     * @Route("/events/history/user/detail/{id}", name="detail_history")
     */
    public function test(ManagerRegistry $doctrine, Event $event): Response
    {
        // $event = $doctrine -> getRepository(Event::class) -> findby(['id' => $this -> getUser()], []);

        $users = $doctrine -> getRepository(User::class) -> findby(['id' => $this -> getUser()], []);

        return $this -> render('user/detailHistory.html.twig',[            

            'event' => $event,

            'users' => $users,

        ]);

    }


}
