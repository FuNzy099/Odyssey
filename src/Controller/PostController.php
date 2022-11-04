<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Entity\Event;
use App\Form\EditPostType;
use App\Form\CommentEventType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PostController extends AbstractController
{
    /**
     * @Route("/showPost/{id}", name="app_post")
     */
    public function index(ManagerRegistry $doctrine, Post $post = null, Request $request, Event $event): Response
    {
       
        $form = $this -> createForm(CommentEventType::class, $post);

        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){

            // getData => permet de récuperer les data rentré par l'utilisateur dans le formulaire 
            $post = $form -> getData();

            // On instancie un nouvelle objet DateTime
            $now = new DateTime();

        
            $event -> addPost($post);

            // On initialise à l'aide du setter l'utilisateur ayant ecrit le commentaire
            $post -> setUser($this -> getUser());

            // On initialise la date de la creation du commentaire en hydratant avec la variable $now qui contient le nouvelle objet DateTime
            $post -> setCreationDate($now);

            $entityManager = $doctrine -> getManager();

            // On persiste, c'est l'équivalent du prepare() en PDO
            $entityManager -> persist($post);

            // On flush, c'est l'équivalent du execute() en PDO
            $entityManager -> flush();

            // On affiche un flash indiquant le succès à l'utilisateur
            $this -> addFlash('message', 'Votre commentaire est bien ajouté !');

            // On fait une redirection vers les commentaires de l'évènement en question
            return $this -> redirectToRoute('app_post', ['id' => $event -> getId()]);

        }

        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'event' => $event,
            'formComment' => $form -> createView()
 
        ]);
    }

    
    /**
     * @Route("/post/{id}/edit", name="edit_post")
     */
    public function edit(ManagerRegistry $doctrine, Post $post = null, Request $request): Response
    {
        if ($this -> getUser() && $this -> getUser() == $post -> getUser() ) {

            $form = $this->createForm(CommentEventType::class, $post);

            $form -> handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $doctrine -> getManager();

                $entityManager -> persist($post);

                $entityManager -> flush();

                $this -> addFlash('message', 'Le message a bien été modifier !');

                // return $this -> redirectToRoute('app_post');
                
            }

        }

        return $this->render('post/edit.html.twig', [
            'editPost' => $form->createView(),
        ]);
    }

}
