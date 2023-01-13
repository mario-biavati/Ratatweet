document.querySelector("main form").addEventListener("submit", async function (event) {
    event.preventDefault();
    document.querySelector("form > p").innerText = "";
    const username = document.querySelector("#username").value;
    const password = document.querySelector("#password").value;
    if (await login(username, password) == false) {
        document.querySelector("form > p").innerText = "Error! Wrong Username or Password!";
    }
});

function login(username, password) {
    const formData = new FormData();
    formData.append('q', "login");
    formData.append('username', username);
    formData.append('password', password);
    return axios.post('utils/api.php', formData).then(response => {
        console.log(response.data);
        if (response.data["logineseguito"]) {
            location.reload();
            return true;
        } else {
            return false;
        }
    });
}