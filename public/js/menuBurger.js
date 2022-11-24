// https://www.youtube.com/watch?v=lA3OFH0J7TY 39:12



/*
        La déclaration const permet de créer une constante nommée dont la valeur ne peut pas être modifiée par des réaffectations ultérieures.

        La méthode querySelector() retourne le premier element dans le document correspondant au sélecteur.

        DOCUMENTATION :
            const :         https://developer.mozilla.org/fr/docs/Web/JavaScript/Reference/Statements/const
            querySelector : https://developer.mozilla.org/fr/docs/Web/API/Document/querySelector
*/
const iconBurger = document.querySelector('.iconBurger');           // On récupère le conteneur l'icone burger dans le but d'y ajouter un écouteur d'évènement
const mobileOverlay = document.querySelector('.mobileOverlay');     // On récupère le conteneur de la nav mobile

const admin = document.querySelector('#admin');
const even = document.querySelector('#event');
const profil = document.querySelector('#profil');

console.log(admin)

console.log(iconBurger)



/*
    On met en place l'écoute des évènements sur l'icone du menu burger

    addEventListener est une méthode permetant de configurer une fonction qui sera appelée  chaque fois que l'évènement sera livré à la cible
    DOCUMENTATION : https://developer.mozilla.org/en-US/docs/Web/API/EventTarget/addEventListener
*/
iconBurger.addEventListener("click", navBurger);

admin.addEventListener("click", submenuAdmin);
even.addEventListener("click", submenuEvent);
profil.addEventListener("click", submenuProfil);

/*
    Cette fonction sera appellé à chaque fois que l'utilisateur clickera sur l'icone du menu burger.
    Cette fonction aura pour but d'afficher/désafficher la navigation
*/
function navBurger() {

    // Permet d'attribuer la class active à l'iconBurger (Pour l'afficher un burger ou croix)
    iconBurger.classList.toggle('active');


    /*
        Déclaration de la constante navMobile permettant de récuperer le premier element portant le même nom

        DOCUMENTATION :
            querySelector : https://developer.mozilla.org/fr/docs/Web/API/Document/querySelector

    */
    const navMobile = document.querySelector('.navMobile')


    /*
        Condition permetant de verifier si le container navMobile contient la class active
        Si oui on retire la class active
        Si non on ajoute la class active
    */
    if (navMobile.classList.contains('active')) {
        navMobile.classList.remove('active');
        mobileOverlay.classList.remove('active');
    } else {
        navMobile.classList.add('active');
        mobileOverlay.classList.add('active');
    }
}

function submenuAdmin() {

    const dropdownAdmin = document.querySelector('.dropdownBurgerAdmin-content')

    // const dropdownProfil = document.querySelector('.dropdownBurgerProfil-content')

    if (dropdownAdmin.classList.contains('active')) {
        dropdownAdmin.classList.remove('active');


    } else {
        dropdownAdmin.classList.add('active');

    }
}

function submenuEvent() {

    const dropdownEvent = document.querySelector('.dropdownBurgerEvent-content')

    if (dropdownEvent.classList.contains('active')) {
        dropdownEvent.classList.remove('active');


    } else {
        dropdownEvent.classList.add('active');

    }
}

function submenuProfil() {

    const dropdownProfil = document.querySelector('.dropdownBurgerProfil-content')

    if (dropdownProfil.classList.contains('active')) {
        dropdownProfil.classList.remove('active');


    } else {
        dropdownProfil.classList.add('active');

    }
}






