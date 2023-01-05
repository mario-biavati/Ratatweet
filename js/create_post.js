let currentSelection = "None";

document.querySelector("main form").addEventListener("submit", function (event) {
    event.preventDefault();
    const titolo = document.querySelector("#titolo").value;
    const descrizione = document.querySelector("#descrizione").value;
    const pic = document.querySelector("input[name='pic']").files[0];
    let idRicetta = -1;
    if(currentSelection=="Crea ricetta") {
        //Formattare ingrediente e quantita
        const procedimento = document.querySelector("#procedimento").value;
        //Creare nuova ricetta
        //Associarla al post
        //Ottenerne l'ID
    }
    else if(currentSelection=="Usa ricetta") {
        idRicetta = document.querySelector('input[name="recipe"]:checked').value;
    }
    new_post(titolo, descrizione, pic, idRicetta);
});

document.querySelector("#crea_ricetta").addEventListener("click", function (event) {
    console.log("Premuto crea ricetta");
    currentSelection = "Crea ricetta";
    let element = document.querySelector("#form_crea_ricetta");
    let hidden = element.getAttribute("hidden");
    if (hidden) {
       element.removeAttribute("hidden");
    } else {
       element.setAttribute("hidden", "hidden");
    }
    //Mutuamente esclusivi
    let element2 = document.querySelector("#ricette_salvate");
    let hidden2 = element2.getAttribute("hidden");
    if (!hidden2) {
       element2.setAttribute("hidden", "hidden");
    }
});

document.querySelector("#usa_ricetta").addEventListener("click", function (event) {
    console.log("Premuto usa ricetta");
    currentSelection = "Usa ricetta";
    let element = document.querySelector("#ricette_salvate");
    let hidden = element.getAttribute("hidden");
    if (hidden) {
       element.removeAttribute("hidden");
    } else {
       element.setAttribute("hidden", "hidden");
    }
    //Mutuamente esclusivi
    let element2 = document.querySelector("#form_crea_ricetta");
    let hidden2 = element2.getAttribute("hidden");
    if (!hidden2) {
       element2.setAttribute("hidden", "hidden");
    }
});

function new_post(titolo, descrizione, pic, idRicetta) {
    const formData = new FormData();
    formData.append('q', "new_post");
    formData.append('titolo', titolo);
    formData.append('descrizione', descrizione);
    formData.append('IDricetta', idRicetta);
    var reader = new FileReader();
    reader.onload = () => {
        formData.append('pic', btoa(reader.result));
        axios.post('utils/api.php', formData, {
            headers: {
              "Content-Type": "multipart/form-data",
            }
            }).then(response => {
            console.log(response.data);
            if (response.data["esito"]==true) {
                console.log("ACK");
                id_post = response.data["IDpost"];
                location.href="post.php?id="+id_post;
            } else {
                console.log(response.data["esito"]);
                console.log("NACK");
                document.querySelector("form > p").innerText = response.data["errore"];
            }
            }).catch(function (error) {
                if (error.response) { // get response with a status code not in range 2xx
                console.log(error.response.data);
                console.log(error.response.status);
                console.log(error.response.headers);
                } else if (error.request) { // no response
                console.log(error.request);
                // instance of XMLHttpRequest in the browser
                // instance ofhttp.ClientRequest in node.js
                } else { // Something wrong in setting up the request
                console.log('Error', error.message);
                }
                console.log(error.config);
            });
    }
    reader.readAsBinaryString(pic);
}