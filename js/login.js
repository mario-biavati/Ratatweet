document.querySelector("main form").addEventListener("submit", function (event) {
    event.preventDefault();
    const username = document.querySelector("#username").value;
    const password = document.querySelector("#password").value;
    login(username, password);
});

function login(username, password) {
    const formData = new FormData();
    formData.append('q', "login");
    formData.append('username', username);
    formData.append('password', password);
    axios.post('utils/api.php', formData).then(response => {
        console.log(response.data);
        if (response.data["logineseguito"]) {
            location.reload();
        } else {
            document.querySelector("form > p").innerText = response.data["errorelogin"];
        }
    });
}