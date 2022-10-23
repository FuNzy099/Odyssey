/*
    $ c'est le racourci pour jQuery, il permet de :
        - récuperer un sélecteur pour obtenir une collection d'éléments correspondants au DOM
        - Passer une fonction à exécuter lorsque le document est prêt
        - PAsser une chaine de catactères HTML à transformer en élément DOM dans le but de l'injecter au document
        

    .ready() est une fonction intégrée à jQuery qui permet d'attendre que le Document Object Model (DOM) soit chargé entièrement avant d'exécuter le code JavaScript.

    DOCUMENTATIONS : 
        $ ou jQuery :   https://stackoverflow.com/questions/1049112/what-is-the-meaning-of-symbol-in-jquery
                        https://api.jquery.com/jQuery/

        .ready() :      https://learn.jquery.com/using-jquery-core/document-ready/
*/
$(document).ready(function () {

    /*
        La fonction .on() de jQuery permet de lancer un écouteur sur un évenement javascript pour les éléments sélectionnés et les éléments enfants.

        Instruction qui permet d'écouter au click sur le lien(a) portant la class .deleteBtn (dans la modal au sein du fichier listUsers.html.twig) pour exécuter une fonction

        DOCUMENTATIONS:
            .on() : https://api.jquery.com/on/
                    https://grafikart.fr/tutoriels/jquery-on-events-518 (Video de Grafikart (un peu ancienne))
    */
    $('.deleteBtn').on('click', function () {
        /*

            On déclare idUser dans le but de récuperer le lien(a) actuellement cliqué($this) 

            .attr() permet de définir ou de renvoyer les attributs et les valeurs des éléments sélectionnés (à savoir l'id de l'user pour ce cas)
           
            DOCUMENTATIONS: 
                .attr() :   https://api.jquery.com/attr/
                            https://www.w3schools.com/jquery/html_attr.asp
        */
        let idUser = $(this).attr('dataIdUser');
        // la classe removeUser ce situe dans le bouton contenant le lien(a) au sein de la modal dans le fichier listUsers.html.twig
        $('.removeUser').attr('dataIdUser', idUser);
    });


    $(".removeUser").on('click', function () {
        let removeUrl = $(this).attr('dataIdUser');
        $.ajax({
            url: removeUrl,
            // Permet de rediriger en cas de succès vers la route "users", route qui dirige vers la liste des users enregistré sur le site 
            success: function (response) {
                window.location.href = "users";
            },
        });
    });
});