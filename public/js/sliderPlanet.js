// On déclare les variables globales
let compteur = 0; // C'est un compteur qui permettra de savoir sur quelle slide on ce trouve
let timer, elements, slides, slideWidth, speed, transition

window.onload = () => {
    /*
        La déclaration const permet de créer une constante nommée dont la valeur ne peut pas être modifiée par des réaffectations ultérieures.
        DOCUMENTATION : https://developer.mozilla.org/fr/docs/Web/JavaScript/Reference/Statements/const

        On récupère le conteneur principal de la diapo.
        La méthode querySelector() retourne le premier element dans le document correspondant au sélecteur, ici à l'occurence .diapo
    */
    const diapo = document.querySelector(".diapo");
    const dots = document.querySelector(".dotsClick")



    // On récupère le conteneur de tous les éléments
    elements = document.querySelector(".elements");


    /*
        On récupère les enfants du selecteur elements (.children) pour en faire un tableau (Array.from)
 
        DOCUMENTATION :
            Array.from : https://developer.mozilla.org/fr/docs/Web/JavaScript/Reference/Global_Objects/Array/from
            .children : https://developer.mozilla.org/fr/docs/Web/API/Element/children
    */
    slides = Array.from(elements.children);  // tableau contenant toutes les sliders
    dotsPlanets = Array.from(dots.children); // Tableau contenant toutes les planetes

    /*
       On récupère la largeur d'une slide à l'aide de la méthode getBoundingClientRect() 
       Elle retourne un objet en fournissant des informations sur la taille d'un élément et sa position par rapport à la zone d'affichage

       DOCUMENTATION :
            getBoundingClientRect() : https://developer.mozilla.org/fr/docs/Web/API/Element/getBoundingClientRect
    */
    slideWidth = diapo.getBoundingClientRect().width;


    // On récupère les flèches
    let next = document.querySelector("#nav-right");
    let prev = document.querySelector("#nav-left");


    /*
        On mes en place l'écoute des évènements sur les flèches

        addEventListener est une méthode permetant de configurer une fonction qui sera appelée  chaque fois que l'évènement sera livré à la cible
        DOCUMENTATION : https://developer.mozilla.org/en-US/docs/Web/API/EventTarget/addEventListener
    */
    next.addEventListener("click", ()=>{
        plusSlides(1)
    })

    prev.addEventListener("click", ()=>{
        plusSlides(-1)
    })


    /*
        On réalise un slide vers la gauche automatiquement toutes les 10 secondes

        setInterval() est une méthode qui permet de répéter une fonction ou d'exécuter un extrait de code à interval régulier
        DOCUMENTATION : https://developer.mozilla.org/en-US/docs/Web/API/setInterval
    */
    timer = setInterval(showSlides, 10000);


    /*
        On ajoute un écouteur d'évènement pour gerer le survol de la souris

        addEventListener est une méthode permetant de configurer une fonction qui sera appelée  chaque fois que l'évènement sera livré à la cible
        DOCUMENTATION : https://developer.mozilla.org/en-US/docs/Web/API/EventTarget/addEventListener
    */
    diapo.addEventListener("mouseover", stopTimer);
    diapo.addEventListener("mouseout", startTimer);
    // dots.addEventListener('mouseover', stopTimer )

    
    let nextClick = document.querySelector("#nav-right");
    nextClick.addEventListener("click", stopTimer);

    let prevClick = document.querySelector("#nav-left");
    prevClick.addEventListener("click", stopTimer);
    

    for (i = 0; i < dotsPlanets.length; i++) {
     
        dotsPlanets = document.querySelector('.dotsClick')
        dotsPlanets.addEventListener('click', stopTimer)
    }

}



// Function pour start le timer quand la souris ne survol plus le slider
function stopTimer() {
    clearInterval(timer);
}


// Function pour rénitialiser le timer
function startTimer() {
    timer = setInterval(showSlides);
   

}



                                            // PARTIE CORESPONDANT AU DOTS

// On déclare slideIndex qui correspond à la premiere image du slider
let slideIndex = 1;
showSlides(slideIndex);


// Function permettant de défiler vers la droite ou la gauche en cliquant sur les flèches
function plusSlides(n) {
    showSlides(slideIndex += n);
   
}
plusSlides;

function currentSlide(n) {
    showSlides(slideIndex = n);
}
currentSlide;



function showSlides(n) {

    let i;
    let slides = document.getElementsByClassName("element");

    let dots = document.getElementsByClassName("dot");

    // Quand le slider arrive à la derniere image on recommence à la premiere (défilement automatique ou manuel)
    if (n > slides.length) {slideIndex = 1}
    
    // Quand le slider arrive à la premiere image on recommence à la derniere (défillement manuel)
    if (n < 1) {slideIndex = slides.length}
    
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
     
    slides[slideIndex-1].style.display = "flex";
    dots[slideIndex-1].className += " active";

    clearTimeout(timer);
    timer = setTimeout(() => plusSlides(1), 10000)

    // if(window.matchMedia("(min-width: 768px)").matches){
        
    //     truc = document.querySelector('.moreDetail')

    //     truc.style.color = 'red'
       
    // } else if(window.matchMedia("(min-width: 767px)").matches){
    //     truc = document.querySelector('.moreDetail')

    //     truc.style.color = 'yellow'
    // }

}


   


