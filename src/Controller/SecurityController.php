<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfilType;
use App\Form\EditPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{



    //  --------------------- FUNCTION QUI PERMET DE CE CONNECTER

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        if (!$this->getUser()) {

            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
            
            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

        } else {
            return $this->redirectToRoute('app_home');
        }
    }



    //  --------------------- FUNCTION QUI PERMET DE CE DECONNECTER

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }



    //  --------------------- FUNCTION QUI PERMET D'EDITER LE PROFIL

    /**
     * @Route("/user/profil/edit", name="edit_profil")
     */
    public function editProfil(ManagerRegistry $doctrine, Request $request)
    {

        // Si il y a un utilisateur en session
        if ($this->getUser()) {

            // On récupére le user en Session
            $user = $this->getUser();

            
            /*
                On récupère differents information de l'utilisateur (Pseudo, numéro de téléphone et email),
                Ces variables sont là dans le but d'effectuer des conditions un peut plus tard dans la function
            */ 
            $actualPseudo = $user -> getPseudonyme();
            $actualPhoneNumber = $user -> getPhoneNumber();
            $actualEmail = $user -> getEmail();
      
            // la function createForm() => permet de construire un formulaire qui ce repose sur le builder de EditProfilType qui lui même ce repose sur l'entity User
            $form = $this->createForm(EditProfilType::class, $user);

            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {

                /*
                    $newPseudo   => On récupère le pseudonyme saisi dans le formulaire
                    $checkPseudo => On effectue une requete DQL à l'aide du nouveau pseudo dans le but de voir si il est existant dans la base de donnée
                */
                $newPseudo = $form->get('pseudonyme')->getData();
                $checkPseudo = $doctrine->getRepository(User::class)->findOneBy(['pseudonyme' => $newPseudo], []);

                /*
                    $newPhoneNumber   => On récupère le numéro de telephone saisi dans le formulaire
                    $checkPhoneNumber => On effectue une requete DQL à l'aide du nouveau n° de tel dans le but de voir si il est existant dans la base de donnée
                */
                $newPhoneNumber = $form -> get('phoneNumber') -> getData();
                $checkPhoneNumber = $doctrine -> getRepository(User::class) -> findOneBy(['phoneNumber' => $newPhoneNumber], []);

                /*
                    $newEmail  => On récupère l'email saisi dans le formulaire
                    $checkEmail => On effectue une requete DQL à l'aide du nouveau email dans le but de voir si il est existant dans la base de donnée
                */
                $newEmail = $form -> get('email') -> getData();
                $checkEmail = $doctrine -> getRepository(User::class) -> findOneBy(['email' => $newEmail], []);

                /*
                    $newAvatar => On récupère l'image saisi dans le formulaire
                */
                $newAvatar = $form -> get('avatar') -> getData();

                // dd($newAvatar);


                /*
                    PARTIE CONCERNANT LA MODIFICATION DU PSEUDONYME
                
                    On verifie si le nouveau pseudo est different au pseudo actuel
                */
                if($newPseudo != $actualPseudo){
            

                    //  On verifie si le nouveau pseudo n'est pas déjà dans la base de donnée
                    if (!isset($checkPseudo)){
    
                        // On passe par un manager pour récupérer depuis l'objet $doctrine notre getManager(), c'est grace à cette méthode qu'on à accès à persist() et flush()
                        $entityManager = $doctrine->getManager();
    
                        // On persist() notre objet user, c'est l'équivalent de prepare() en PDO
                        $entityManager->persist($user);
    
                        // On flush(), c'est l'équivalent de execute() en PDO, c'est dans cette étape flush() que notre insert into ce fait !
                        $entityManager->flush();
    
                        // Si tout à fonctionner on affiche le message
                        $this->addFlash('message', 'Profil mis à jour');
    
                        // Redirection
                        return $this->redirectToRoute('edit_profil');
    
                        
                    // Sinon, si le nouveau pseudo existe dans la base de donnée on affiche un message à l'utilisateur !
                    } else {
    
                        $this->addFlash('warning', 'Le pseudonyme est déjà utilisé.. Veuillez en choisir un autre !');
    
                        //  reedirection
                        return $this->redirectToRoute('edit_profil');
                    }
                    
                }


                /*
                    PARTIE CONCERNANT LA MODIFICATION DU NUMERO DE TEL
                
                    On verifie si le nouveau numéro de telephone est different au numéro déjà enregistré
                */
                if($newPhoneNumber != $actualPhoneNumber){

                    // On verifie si le nouveau numéro de telephone n'est pas en base de donnée
                    if (!isset($checkPhoneNumber)){
    
                        //  On passe par un manager pour récupérer depuis l'objet $doctrine notre getManager(), c'est grace à cette méthode qu'on à accès à persist() et flush()
                        $entityManager = $doctrine->getManager();
    
                        // On persist() notre objet user, c'est l'équivalent de prepare() en PDO
                        $entityManager->persist($user);
    
                        // On flush(), c'est l'équivalent de execute() en PDO, c'est dans cette étape flush() que notre insert into ce fait !
                        $entityManager->flush();
    
                        // Si tout à fonctionner on affiche le message
                        $this->addFlash('message', 'Profil mis à jour');
    
                        //  Redirection
                        return $this->redirectToRoute('edit_profil');
    
                    // Sinon, si le numéro de telephone existe dans la base de donnée on affiche un message à l'utilisateur !
                    } else {
    
                        $this->addFlash('warning', 'Une erreur est survenue !');
    
                        // Redirection
                        return $this->redirectToRoute('edit_profil');
                    }
   
                }

 
                /*
                    PARTIE CONCERNANT LA MODIFICATION DE L'EMAIL
                
                    On verifie si le nouveau numéro de telephone est different au numéro déjà enregistré
                */
                if($newEmail != $actualEmail){

                    if (!isset($checkEmail)){
    
                        $entityManager = $doctrine->getManager();
    
                        $entityManager->persist($user);
    
                        $entityManager->flush();
    
                        $this->addFlash('message', 'Profil mis à jour');
    
                        return $this->redirectToRoute('edit_profil');
    
                        
                    } else {
    
                        $this->addFlash("warning", "L'adresse Email est déjà utilisée.. Veuillez en saisir une autre !");
    
                        return $this->redirectToRoute('edit_profil');
                    }
   
                }


                /*
                    PARTIE CONCERNANT LA MODIFICATION DE L'AVATAR

                    On verifie si il existe un nouvelle avatar 
                */   
                if(isset($newAvatar)){

                    /*
                        Condition permetant de verifier si l'image que l'utilisateur souhaite mettre en avatar est dans un format autorisé

                        in_array => Permet d'indiquer si une valeur appartient au tableau

                        DOCUMENTATION : 
                            in_array : https://www.php.net/manual/fr/function.in-array.php
                    */
                    if(in_array($newAvatar -> guessExtension(), ["png", "jpeg", "jpg", "webp" ])){

                        // Condition dans l'optique de définir un poid maximum autorisé pour l'avatar, ici à l'occurrence 2Mo max !
                        if($newAvatar -> getSize() <= 2000000){
                            
                            /*
                                On hache et applique une iniqid au fichier

                                md5 => C'est un algorithme de hachage faible
                                uniqId => Permet de génèrer un identifiant unique basé sur la date et l'heure courante 

                                DOCUMENTATION: 
                                    md5 : https://www.php.net/manual/fr/function.md5.php
                                    uniqid : https://www.php.net/manual/fr/function.uniqid.php
                            */
                            $file = md5(uniqid()) . '.' . $newAvatar -> guessExtension();
                    
                            /*
                                On déplace le fichier avatar de l'utilisateur dans le répertoire public/img/avatars
                                Pour ce faire j'ai declaré avatar_directory dans les parameters du fichier services.yaml,
                            */ 
                            $newAvatar -> move(
                                $this -> getParameter('avatar_directory'),
                                $file
                            );

                            /*
                                On déclare nameActualAvatar dans le but de récuperer dans notre disque local l'avatar actuel avant modification par l'utilisateur,
                                pour ce faire j'utilise de nouveau le getParameter('avatar_directory') pour localiser le fichier dans public/img/avatars 
                            */ 
                            $nameActualAvatar = $this -> getParameter('avatar_directory') . '/' . $user -> getAvatar();


                            // Condition pour verifier si $nameActualAvatar est differents que l'avatar par defaut de l'application ! 
                            if($nameActualAvatar != $this -> getParameter('avatar_directory') . '/default-avatar.png'){
                        
                                /*
                                    Condition pour verifier si $nameActualAvatar existe, si c'est le cas nous supprimon l'ancien avatar 

                                    file_exists() => Permet de verifier si un fichier ou dossier existe
                                    unlink() => permet de supprimer le fichier ou dossier

                                    DOCUMENTATION:
                                        file_exists() : https://www.php.net/manual/fr/function.file-exists.php
                                        unlink() : https://www.php.net/manual/fr/function.unlink.php
                                */ 
                                if(file_exists($nameActualAvatar) ){
                                    unlink($nameActualAvatar);
                                }

                            }
               
                            // On initialiser le nouvelle avatar de l'utilisateur
                            $user -> setAvatar($file);
                            
                            // On déclare $entityManager pour récupèrer getManager(), c'est grace à lui qu'on accéde à persist() et flush()
                            $entityManager = $doctrine -> getManager();
                        
                            // On persit() notre user, c'est l'équivalent de prepare() en PDO
                            $entityManager -> persist($user);

                            
                            // On flush(), c'est l'équivalent de execute() en PDO, c''est à cette étape que le inser_into ce fait !
                            $entityManager -> flush();

                            // Message
                            $this -> addFlash('message', "Profil mis à jour");

                        // Si le poid du fichier dépasse les 2 Mo, on affiche un message à l'utilisateur
                        } else {

                            $this -> addFlash('warning', "Votre fichier avatar pèse plus de 2 Mo ! ");

                        }

                    // Si le format du ficher n'est pas autorisé on affiche un message
                    } else {

                        $this -> addFlash('warning', "Le format du fichier de l'avatar n'est pas supporté .. Veuillez choisir un autre format !");

                    }
        
                }
                
            }

            return $this->render('security/editProfil.html.twig', [
                'formEditProfil' => $form -> createView(),
                'users' => $user,
            ]);

        // Si il n'y à pas d'utilisateur en session on redirige vers le home du site
        } else {

            return $this->redirectToRoute('app_home');

        }

    }



    //  --------------------- FUNCTION QUI PERMET DE MODIFIER LE MOT DE PASSE

    /**
     * @Route("/user/profil/editPassword", name="edit_password")
     */
    public function editPassword(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher)
    {

        // On verifie si il y a un user en session
        if($this -> getUser()){

            // On récupère le user en session
            $user = $this->getUser();

            // La function createForm() => permet de construire un formulaire qui ce repose sur builder de editPasswordType qui lui même ce repose sur l'entity User
            $form = $this->createForm(EditPasswordType::class, $user);

            // La function hendleReques permet de récupérer et analyser les données saisie dans le formulaire en méthode POST, c'est en gros le sasse entre la saisi du formulaire et l'envoie dans la base de donnée
            $form->handleRequest($request);

            if($form -> isSubmitted() && $form -> isValid()){

                // On récupère le mot de passe hashé dans la base de donnée de l'utilisateur
                $actualPasswordBd = $user -> getPassword();

                // Correspond à la saisi du mot de passe actuel de l'utilisateur
                $actualPassword = $form -> get('actualPassword') -> getData();

                // On récupère le nouveau mot de passe saisi dans le formulaire
                $newPassword = $form->get('plainPassword')->getData();
                

                /*
                    On vérifie si le mot de passe saisi dans le formulaire est identique au mot de passe hashé enregistré dans la base de donnée,
                    pour ce faire on utilise la méthode password_verify !

                    password_verify => Permet de verifier que le hachage donné correspond au mot de passe donné.

                        DOCUMENTATION: 
                            https://www.php.net/manual/en/function.password-verify.php
                */
                if(password_verify($actualPassword, $actualPasswordBd)){

                    /*
                        On récupère le setPassword dans notre objet user pour initialiser le mot de passe.

                        hashPassword => Permet de hacher le nouveau mot de passe de l'utilisateur avec l'algoritme le meilleur du moment pour cette version de symfony
                        L'algoritme peutè$etre changé dans le fichier dans packages/security.yaml

                        DOCUMENTATION:
                            Hachage du password: https://symfony.com/doc/current/security/passwords.html#configuring-a-password-hasher
                    */
                    $user -> setPassword($passwordHasher -> hashPassword($user,  $newPassword));

                    // On déclare un $entityManager pour récupérer getManager()
                    $entityManager = $doctrine -> getManager();

                    // On flush(), c'est l'équivalent du execute() en PDO
                    $entityManager -> flush();

                    // Message
                    $this -> addFlash("message", "Votre mot de passe a été modifier");

                    // redirection
                    return $this -> redirectToRoute('edit_password');

                // Si le mot de passe saisi dans le formulaire n'est pas identique à celui de enregistrer en base de donnée on affiche un message
                } else {

                   $this -> addFlash("warning", "Votre mot de passe est incorect");

                   return $this -> redirectToRoute('edit_password');

                };

            }

            return $this->render('security/editPassword.html.twig', [
                'formEditPassword' => $form -> createView(),
            ]);

        // Si il n'y a pas d'utilisateur en session on le redirige vers la page d'acceuil du site
        } else {

            return $this -> redirectToRoute('app_home');

        }
       

    }



    // --------------------- FUNCTION QUI PERMET DE DE RESET PASSWORD


}
