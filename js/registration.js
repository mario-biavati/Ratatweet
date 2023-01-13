document.querySelector("main form").addEventListener("submit", async function (event) {
    event.preventDefault();
    document.querySelector("form > p").innerText = "";
    const username = document.querySelector("#username").value;
    const password = document.querySelector("#password").value;
    const bio = document.querySelector("#bio").value;
    const pic = document.querySelector("input[name='pic']");
    let i = await register(username, password, bio, pic);
    if (i == -1) {
        document.querySelector("form > p").innerText = "Error! You must choose an Username and a Password!";
    } else if (i == -2) {
        document.querySelector("form > p").innerText = "Error! Username is not available!";
    } else if (i == -3) {
        document.querySelector("form > p").innerText = "Error! Select an image for your Profile Picture!";
    }
});

function register(username, password, bio, picElem) {
    if (username == '' || password == '') {
        return -1;
    }
    if (picElem.files.length == 0) return -3;
    let pic = picElem.files[0];

    const formData = new FormData();
    formData.append('q', "register");
    formData.append('username', username);
    formData.append('password', password);
    formData.append('bio', bio);
    var reader = new FileReader();
    return new Promise(resolve => {
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
                    resolve(0);
                } else {
                    console.log("NACK");
                    resolve(-2);
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
    });
}