const dateInput = document.querySelector("#datepicker");

dateInput.addEventListener('change',(e)=>{
    e.preventDefault();
    nasarequested();
})

function nasarequested() {

    // Déclaration des constante
    const baseUrl = 'https://api.nasa.gov/planetary/apod?api_key=';
    const apiKey = "qp8YWZaK0WJ3ANoL2oKonYl14vtogAvWvHlfBpBP";
    const title = document.querySelector("#title");
    const copyright = document.querySelector("#copyright");
    const mediaSection = document.querySelector("#media-section");
    const information = document.querySelector("#description");
    const currentDate = new Date().toISOString().slice(0, 10);

    const imageSection = 
    `<a id="hdimg" href="" target="-blank">
        <figure class="image-div">
            <img id="image_of_the_day" src="" alt="image journaliere de la nasa">
        </figure>
     </a>`

    const videoSection = `<div class="video-div"> <iframe id="videoLink" src="" frameborder="0"></iframe></div>`

    let newDate = "&date=" + dateInput.value + "&";

    // Fonction permettant de récupérer les informations
    function fetchData() {
        /* 
            L'instruction try, catch permet de regrouper des instructions à exécuter et de définir une réponse si l'une de ces instructions provoque une exception

            DOCUMENTATION : 
                try, catch : https://developer.mozilla.org/fr/docs/Web/JavaScript/Reference/Statements/try...catch
                             https://fr.javascript.info/try-catch
        */ 
        try {
            // Je récupére la constante baseUrl + apiKey + newDate
            fetch(baseUrl + apiKey + newDate)
                .then(response => response.json())
                .then(json => {
                    console.log(json)
                    displayData(json)
                })
        } catch (error) {
            console.log(error)
        }
        
    }

    // Fonction permettant de lier les data aux élements HTML
    function displayData(data) {

        // Titre de l'image
        title.innerHTML = data.title; 

        // Déscription de l'image
        information.innerHTML = data.explanation

        /*
            Permet de verifier si l'image détient un copyright,
            pour ce faire j'utilise la méthode me retournant un booléen me permettant
            de vérifier si l'objet possède ou non la propriété copyright

            DOCUMENTATION:
                hasOwnProperty :    https://developer.mozilla.org/fr/docs/Web/JavaScript/Reference/Global_Objects/Object/hasOwnProperty
        */ 
        if (data.hasOwnProperty("copyright")) {
            copyright.innerHTML = data.copyright;
        } else {
            copyright.innerHTML = ''
        }

        // Permet de verifier si l'image du jours de l'API est une photo ou une video
        if (data.media_type == "video") {
            mediaSection.innerHTML = videoSection;
            document.getElementById("videoLink").src = data.url;

        } else {
            mediaSection.innerHTML = imageSection;
            document.getElementById("hdimg").href = data.hdurl;             // Permet d'afficher l'image dans un nouvelle onglet 
            document.getElementById("image_of_the_day").src = data.url;     // Permet d'afficher l'image au sein de l'API
        }

        date.innerHTML = data.date
        dateInput.max = currentDate;
        dateInput.min = "1995-06-16";

    }
   
    fetchData();
    

}

nasarequested();