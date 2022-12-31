document.querySelector("main form").addEventListener("submit", function (event) {
    event.preventDefault();
    const username = document.querySelector("#username").value;
    const password = document.querySelector("#password").value;
    const bio = document.querySelector("#bio").value;
    const pic = document.querySelector("#pic").value;
    register(username, password, bio, pic);
});

function register(username, password, bio, pic) {
    const formData = new FormData();
    formData.append('q', "register");
    formData.append('username', username);
    formData.append('password', password);
    formData.append('bio', bio);
    formData.append('pic', pic);
    axios.post('utils/api.php', formData).then(response => {
        console.log(response.data);
        if (response.data["esito"]==true) {
            location.href="user_page.php";
        } else {
            document.querySelector("form > p").innerText = response.data["errore"];
        }
    });
}