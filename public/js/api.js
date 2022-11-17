// https://dev.to/msdeshmukh009/how-to-use-nasa-s-apod-api-2iec

const dateInput = document.querySelector("#datepicker");
dateInput.addEventListener('change',(e)=>{
    e.preventDefault();
    nasarequested();
})

function nasarequested() {
    const baseUrl = 'https://api.nasa.gov/planetary/apod?api_key=';
    const apiKey = "qp8YWZaK0WJ3ANoL2oKonYl14vtogAvWvHlfBpBP";
    const title = document.querySelector("#title");
    const copyright = document.querySelector("#copyright");
    const mediaSection = document.querySelector("#media-section");
    const information = document.querySelector("#description");
    const currentDate = new Date().toISOString().slice(0, 10);

    const imageSection = `<a id="hdimg" href="" target="-blank">
     <figure class="image-div">
     <img id="image_of_the_day" src="" alt="image journaliere de la nasa">
     </figure>
     </a>`

    const videoSection = `<div class="video-div"> <iframe id="videoLink" src="" frameborder="0"></iframe></div>`

    let newDate = "&date=" + dateInput.value + "&";

    function fetchData() {
        try {
            fetch(baseUrl + apiKey + newDate)
                .then(response => response.json())
                .then(json => {
                    console.log(json)
                    displaydata(json)
                })
        } catch (error) {
            console.log(error)
        }
        
    }

    function displaydata(data) {

        title.innerHTML = data.title;

        if (data.hasOwnProperty("copyright")) {
            copyright.innerHTML = data.copyright;
        } else {
            copyright.innerHTML = ''
        }

        date.innerHTML = data.date
        dateInput.max = currentDate;
        dateInput.min = "1995-06-16";

        if (data.media_type == "video") {
            mediaSection.innerHTML = videoSection;
            document.getElementById("videoLink").src = data.url;

        } else {
            mediaSection.innerHTML = imageSection;
            document.getElementById("hdimg").href = data.hdurl;
            document.getElementById("image_of_the_day").src = data.url;
        }

        information.innerHTML = data.explanation

    }
    
    fetchData();
    

}

nasarequested();