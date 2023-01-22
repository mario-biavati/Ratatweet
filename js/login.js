document.querySelector("main form").addEventListener("submit", async function (event) {
    event.preventDefault();
    document.querySelector("form > p").innerText = "";
    const username = document.querySelector("#username").value;
    const password = document.querySelector("#password").value;
    const result = await login(username, password);
    if (result.logineseguito == false) {
        document.querySelector("form > p").innerText = result.errorelogin;
    }
});

function login(username, password) {
    const formData = new FormData();
    formData.append('q', "login");
    formData.append('username', username);
    formData.append('password', password);
    return axios.post('utils/api.php', formData).then(response => {
        if (response.data["logineseguito"]) {
            location.reload();
        }
        return response.data;
    });
}