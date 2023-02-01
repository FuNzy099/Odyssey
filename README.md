# Space Odyssey - Projet de fin de parcours - Réalisé en Symfony

## Les langages et outils utilisé :

Lors de la réalisation de mon projet de fin de parcours au titre de développeur web et web mobile de niveau 5 j'ai pu utilisé differents outils et langages ! 

### Framework

![image](https://user-images.githubusercontent.com/109731213/210215766-ebdbd00a-5dcf-4f65-b1f0-8754d98ae316.png) ![image](https://user-images.githubusercontent.com/109731213/210215738-2cc52e9b-0e9d-479b-b0ca-7b4de0ec73a6.png)

### Langages

![image](https://user-images.githubusercontent.com/109731213/210215862-d83a2586-6a2b-47bb-8d79-b6377eb63d68.png) ![image](https://user-images.githubusercontent.com/109731213/210215876-1e11569d-92dd-4013-a4d5-02c076ab4e18.png) ![image](https://user-images.githubusercontent.com/109731213/210216921-6fb31c29-fc4b-4a2a-8ca6-2ba3039dcdb1.png) ![image](https://user-images.githubusercontent.com/109731213/210216070-846edab9-da01-4e75-9fba-53a592906eba.png) ![image](https://user-images.githubusercontent.com/109731213/210216085-424cc68b-a267-4ab1-a3b9-bcfc9eef408e.png) 


### Outils

![image](https://user-images.githubusercontent.com/109731213/210216873-7bfdcd9c-ff0e-4325-898a-da1cefe4ec32.png) ![image](https://user-images.githubusercontent.com/109731213/210216879-db5ff25d-58dd-4df1-9712-437fa86d1099.png)

### API

J'ai également pu consomer une API du nom de APOD : Astronomy Picture Of the Day.  
Cette API permet d'afficher une photo unique par jours provenant de la NASA


# Résumé de ce projet :

Etant amateur d'astronomie et voulant réalisé un projet eyant du sens à mes yeux, j'ai décidé de concevoir Space Odyssey qui est une application permetant à un utilisateur de créer ou de participer à un évènement sur le theme de l'astronomie.  
Cette application ce veux entierement gratuite et tous public permettant ainsi un moment de partage, de découverte et d'apprentissage de notre ciel !    

### Les fonctionnalités principales de l'application sont les suivantes :   

#### Un visiteur :

* Il peut consulter les évènements sans pouvoir si inscrire.
* Il peut s'inscrire et se connecter à l'application.

#### Un utilisateur :

* Il peut créer un évènement ou s'inscrire à un évènement d'une tiers personne.
* Il peut retrouver la liste des évènements qu'il organise ou participes dont la date n'est pas encore passé.
* Il peut également retrouver l'historique de ces évenements organisé ou participé dont la date est passé.
* Il peut appliquer un filtre dans le but de retrouver des évènements correspondant à ces critères de recherche (Titre, date de debut, date de fin, code postal et ville).
* L'utilisateur inscrit à un évènement peut poster un message au sein de ce dernier dans l'optique de communiquer avec les autres utilisateur inscrit dans ce même évènement.
* L'utilisateur peut également communiquer avec un autre utilisateur à l'aide de la messagerie privée.

### Les contraintes de l'application :   

* Un utilisateur ne peut pas modifier ou supprimer un évènement ou un profil qui ne lui appartient pas.
* Si un évènement est complet l'inscription à celui-ci n'est pas possible.
* Un utilisateur ne peut plus poster de message dans un évènement si la date de fin de celui-ci est passé.
* Une interface d'administration est accessible pour les utilisateurs eyant le role admin.
* L'application répond également aux normes de sécurité notamment concernant la faille upload, l'injection SQL, la faille csrf, la faille xss, l'attaque par force brute et par dictionnaire, le mot de passe de l'utilisateur est également haché.
* Le responsive design est également mis en oeuvre, à noter que l'ensemble de l'application n'est pas responsive, pour le moment seul la page d'acceuil ainsi que la page des évnements l'est.

### LE RGPD

Mon application Space Odyssey doit également répondre aux attentes du RGPD, pour ce faire j'ai appliquer les démarches suivantes:  

* La __minimisation des données__, uniquement les données essentiel à l'application sont récoltés.
* Le __droit d'accès__, L'utilisateur peut à tout moment consulter et modifier l'intégralité de ces informations personnelles.
* L'__obligation de sureté__, l'application devras répondre aux normes de sécurité.
* Le __consentement__, J'ai veillé à récolté le consentement de l'utilisateur lors de ce son inscription à l'application.

