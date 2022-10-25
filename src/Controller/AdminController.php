<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Event;
use App\Form\AdminEditUserType;
use App\Form\AdminEditEventType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @isGranted("ROLE_ADMIN")
 * 
 * isGranted est le moyen le plus simple de restreindre l'accès, cette annotation permet de verifier si le role admin est attribuer au user
 * pour pouvoir acceder à l'enssemble des méthodes de cette class.
 * 
 * DOCUMENTAION:
 *      isGranted : https://symfony.com/bundles/SensioFrameworkExtraBundle/current/annotations/security.html#isgranted
 */
class AdminController extends AbstractController
{

    public function __construct(ManagerRegistry $doctrine)
    {
        $this -> doctrine = $doctrine;
    }
 
    /**
     * @Route("/admin", name="admin_home")
     * 
     * Route permetant d'afficher des informations global de l'application à l'administrateur
     */
    public function adminPanel(): Response
    {

        /*
            IS_AUTHENTICATED_FULLY: Permet de verifier si un utilisateur est connecté, ce n'est pas un rôle mais il agit en quelque sorte comme tel,
                                    car chaque utilisateur qui s'est connecté l'aura !

            DOCUMENTATION:
                IS_AUTHENTICATED_FULLY : https://symfony.com/doc/current/security.html#setting-individual-user-permissions
        */ 
        $this -> denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $users = $this -> doctrine -> getRepository(User::class) -> findAll();

        $events = $this -> doctrine -> getRepository(Event::class) -> findAll();

        return $this->render('admin/index.html.twig', [

            'users' => $users,
            'events' => $events

        ]);
    }

                                                    // PARTIE CONCERNANT LES UTILISATEUR

    /**
     * @Route("/admin/users", name="admin_users")
     * 
     * Route permetant d'afficher la liste des utilisateurs enregistrés sur l'application
     */
    public function listUsers() : Response
    {

        $this -> denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $users = $this -> doctrine -> getRepository(User::class) -> findBy([], ['pseudonyme' => 'ASC']);

        return $this->render('admin/listUsers.html.twig', [
            'users' => $users
        ]);
    }



    /**
     * @Route("/admin/users/edit/{id}", name="admin_edit_user")
     * 
     * Route permetant d'editer le profil d'un utilisateur (Pseudonyme et role)
     */
    public function editUser(Request $request,User $user) : Response
    {

        $this -> denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this -> createForm(AdminEditUserType::class, $user);

        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){

            $entityManager = $this -> doctrine -> getManager();

            $entityManager -> persist($user);

            $entityManager -> flush();

            $this -> addFlash('message', 'Le profil de l\'utilisateur a bien été mis à jour !');

            return $this -> redirectToRoute('admin_users');
        }

        return $this->render('admin/editUser.html.twig', [
            'formAdminEditUser' => $form -> createView(),
        ]);
    }



    /**
     * @Route("/admin/users/delete/{id}", name="admin_delete_user")
     * 
     * Route permetant de supprimer le profil d'un utilisateur
     */
    public function deleteUser(User $user)
    {

        $this -> denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entityManager = $this -> doctrine -> getManager();

        $entityManager -> remove($user);
        
        $entityManager -> flush();

        $this -> addFlash('message', 'Le profil de l\'utilisateur a bien été supprimé !');
        

        return $this -> redirectToRoute('admin_users');
    }



                                                        // PARTIE CONCERNANT LES UTILISATEURS

   /**
     * @Route("/admin/events", name="admin_events")
     * 
     * Route permetant d'afficher la liste des évènements enregistrés sur l'application
     */
    public function listEvents() : Response
    {

        $this -> denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $events = $this -> doctrine -> getRepository(Event::class) -> findBy([], ['creationDate' => 'DESC']);

        return $this->render('admin/listEvents.html.twig', [
            'events' => $events,   
        ]);
    }



    /**
     * @Route("/admin/events/edit/{id}", name="admin_edit_event")
     * 
     * Route permetant d'editer le profil d'un utilisateur (Pseudonyme et role)
     */
    public function editEvent(Request $request, Event $event) : Response
    {

        $this -> denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this -> createForm(AdminEditEventType::class, $event);

        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){

            $entityManager = $this -> doctrine -> getManager();

            $entityManager -> persist($event);

            $entityManager -> flush();

            $this -> addFlash('message', 'L\'évènement a bien été modifié !');

            return $this -> redirectToRoute('admin_edit_events');
        }

        return $this->render('admin/editEvent.html.twig', [
            'formAdminEditEvent' => $form -> createView(),
        ]);
    }



    /**
     * @Route("/admin/events/delete/{id}", name="admin_delete_event")
     * 
     * Route permetant de supprimer un évènement
     */    
    public function deleteEvent(Event $event)
    {
        $this -> denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entityManager = $this -> doctrine -> getManager();

        $entityManager -> remove($event);

        $entityManager -> flush();

        
        $this -> addFlash('message', 'L\'évènement a bien été supprimé !');
        

        return $this -> redirectToRoute('admin_events');
    }
}
