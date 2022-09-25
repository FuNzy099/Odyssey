<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
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

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }



    /**
     * @Route("/user/profil/edit", name="edit_profil")
     */
    public function editProfil(ManagerRegistry $doctrine, Request $request)
    {

        // ! https://github.com/dean045/ProjetSortir/blob/37b55c19fb698ec9156bc9be2f83db8a22552af4/src/Controller/ProfilController.php

        if ($this->getUser()) {

            // On récupére le user en Session
            $user = $this->getUser();
            $actualPseudo = $user -> getPseudonyme();
            $actualPhoneNumber = $user -> getPhoneNumber();
            $actualEmail = $user -> getEmail();
      
            // la function createForm() => permet de construire un formulaire qui ce repose sur le builder de EditProfilType qui lui même ce repose sur l'entity User
            $form = $this->createForm(EditProfilType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $newPseudo = $form->get('pseudonyme')->getData();

                $checkPseudo = $doctrine->getRepository(User::class)->findOneBy(['pseudonyme' => $newPseudo], []);

                $newPhoneNumber = $form -> get('phoneNumber') -> getData();

                $checkPhoneNumber = $doctrine -> getRepository(User::class) -> findOneBy(['phoneNumber' => $newPhoneNumber], []);

                $newEmail = $form -> get('email') -> getData();

                $checkEmail = $doctrine -> getRepository(User::class) -> findOneBy(['email' => $newEmail], []);

                if($newPseudo != $actualPseudo){

                    if (!isset($checkPseudo)){
    
                        $entityManager = $doctrine->getManager();
    
                        $entityManager->persist($user);
    
                        $entityManager->flush();
    
                        $this->addFlash('message', 'Profil mis à jour');
    
                        return $this->redirectToRoute('edit_profil');
    
                        
                    } else {
    
                        $this->addFlash('warning', 'Le pseudonyme est déjà utilisé.. Veuillez en choisir un autre !');
    
                        return $this->redirectToRoute('edit_profil');
                    }
                    
                }

                if($newPhoneNumber != $actualPhoneNumber){

                    if (!isset($checkPhoneNumber)){
    
                        $entityManager = $doctrine->getManager();
    
                        $entityManager->persist($user);
    
                        $entityManager->flush();
    
                        $this->addFlash('message', 'Profil mis à jour');
    
                        return $this->redirectToRoute('edit_profil');
    
                        
                    } else {
    
                        $this->addFlash('warning', 'Une erreur est survenue !');
    
                        return $this->redirectToRoute('edit_profil');
                    }
   
                }

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

            }

            return $this->render('security/editProfil.html.twig', [
                'formEditProfil' => $form -> createView(),
            ]);

        } else {

            return $this->redirectToRoute('app_home');
        }


        // $user = $this->getUser();
        // $pseudo_user = $user->getPseudonyme();

        // $form = $this->createForm(EditProfilType::class, $user);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     // $oldAvatar = $user->getAvatar();
        //     // $avatarFile = $form['avatar']->getData();
        //     $newPseudo = $form['pseudonyme']->getData();
        //     // if ($avatarFile) {
        //     //     $user->setAvatar('');
        //     //     $fichier = $uploadImage->saveAvatar($avatarFile);
        //     //     if ($oldAvatar && !in_array($oldAvatar, $baseAvatar)) {
        //     //         unlink($this->getParameter('avatar_directory') . '/' . $oldAvatar);
        //     //     }
        //     //     $user->setAvatar($fichier);
        //     // }
        //     if ($newPseudo != $pseudo_user) {
                
        //         $em->flush();
        //         $this->addFlash('success', 'Please check your email for confirmation');
        //         return $this->redirectToRoute('edit_profil');
        //     }
        //     $em->flush();

        //     $this->addFlash('message', 'Your profil has been modified');
        //     // do anything else you need here, like send an email
        //     return $this->redirectToRoute('edit_profil');
        // }

        // return $this->render('security/editProfil.html.twig', [
        //             'formEditProfil' => $form->createView(),
        // ]);
    }
}
