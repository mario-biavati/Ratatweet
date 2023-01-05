document.querySelector("main form").addEventListener("submit", function (event) {
    event.preventDefault();
    const username = document.querySelector("#username").value;
    const password = document.querySelector("#password").value;
    const bio = document.querySelector("#bio").value;
    const pic = document.querySelector("input[name='pic']").files[0];
    register(username, password, bio, pic);
});


function register(username, password, bio, pic) {
    const formData = new FormData();
    formData.append('q', "register");
    formData.append('username', username);
    formData.append('password', password);
    formData.append('bio', bio);
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
                location.href="user_page.php";
            } else {
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