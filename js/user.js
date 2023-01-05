
var logged = -1;
var followButton = document.getElementById("followbutton");
var notificationButton = document.getElementById("notificationbutton");

var posts = document.getElementById("posts");
var arrayPost = [];
var cur_lastPost = 0;
var canPrintPost = true;

document.addEventListener("scroll", () => reloadPosts());

function login() {
    axios.get('template/login_form.php').then(file => {
        document.querySelector("main").innerHTML = file.data;
        var tag = document.createElement("script");
        tag.src = "js/login.js";
        document.querySelector("body").appendChild(tag);
    });
}

function follow() {
    if (logged == -1) login();
    const formData = new FormData();
    formData.append('q', "addFollowed");
    formData.append('idFollowed', id);
    axios.post('utils/api.php', formData);

    followButton.innerHTML="Unfollow";
    followButton.alt="Unfollow user";
    document.getElementById("NumFollowers").textContent = +document.getElementById("NumFollowers").textContent + 1;
    followButton.setAttribute('onclick', 'unfollow()');
    notificationButton.disabled = false;
    enableNotifications();
}
function unfollow() {
    const formData = new FormData();
    formData.append('q', "removeFollowed");
    formData.append('idFollowed', id);
    axios.post('utils/api.php', formData);

    followButton.innerHTML="Follow";
    followButton.alt="Follow user";
    document.getElementById("NumFollowers").textContent = +document.getElementById("NumFollowers").textContent - 1;
    followButton.setAttribute('onclick', 'follow()');
    notificationButton.disabled = true;
}

function enableNotifications() {
    if (logged == -1) login();
    const formData = new FormData();
    formData.append('q', "enableNotifications");
    formData.append('idFollowed', id);
    axios.post('utils/api.php', formData);

    notificationButton.innerHTML="Disable Notifications"; // da cambiare
    notificationButton.alt="Disable notifications";
    notificationButton.setAttribute('onclick', 'disableNotifications()');
}
function disableNotifications() {
    const formData = new FormData();
    formData.append('q', "disableNotifications");
    formData.append('idFollowed', id);
    axios.post('utils/api.php', formData);

    notificationButton.innerHTML="Enable Notifications"; // da cambiare
    notificationButton.alt="Enable notifications";
    notificationButton.setAttribute('onclick', 'enableNotifications()');
}

function printPost(idPost) {
    axios.get('utils/api.php?q=getPost&id=' + idPost).then(r => {
        let post = r.data;
        let htmlContent = 
        `<article id="${post.IDpost}">
            <header>
                <a href="post.php?id=${post.IDpost}">
                    <h1> ${post.title} </h1>
                </a>
                <a href="user_page.php?id=${post.IDuser}">
                    <h2> ${post.username} </h2>
                </a>
                <input type="image" src="img/recipe-icon.png" alt="Save recipe" onclick="saveRecipe(${post.IDrecipe})">
            </header>
            <section>
                <div>
                    <img src="${post.pic}" alt="${post.title}" />
                </div>
            </section>
            <section>
                <div alt="Average rating">
                    <img src="./img/stella_vuota.png" id="${post.IDpost}-Star1" alt="vota 1 stella" />
                    <img src="./img/stella_vuota.png" id="${post.IDpost}-Star2" alt="vota 2 stelle" />
                    <img src="./img/stella_vuota.png" id="${post.IDpost}-Star3" alt="vota 3 stelle" />
                    <img src="./img/stella_vuota.png" id="${post.IDpost}-Star4" alt="vota 4 stelle" />
                    <img src="./img/stella_vuota.png" id="${post.IDpost}-Star5" alt="vota 5 stelle" />
                    <h2>"Average rating: ${post.avgRating}"</h2>
                </div>
                <div alt="Comments number">
                    <h2>${post.numComments}</h2>
                    <a href="post.php?id=${post.IDpost}">
                        <img src="img/comment-icon.png" alt="Guarda commenti" />
                    </a>
                </div>
            </section>
        </article>`;
        posts.innerHTML += htmlContent;
    });
}
function loadPosts(n_post) {
    for (let i = 0; i < n_post; i++) {

        if (i + cur_lastPost >= arrayPost.length) {
            cur_lastPost += i;
            return;
        }
        printPost(arrayPost[i + cur_lastPost]);
    }
    cur_lastPost = cur_lastPost + n_post;
    canPrintPost = true;
}
function isAtBottom() {
    return document.documentElement.clientHeight + window.scrollY >= (document.documentElement.scrollHeight);
}
function reloadPosts() {
    if (isAtBottom() && canPrintPost) {
        canPrintPost = false;
        loadPosts(5);
    }
}

// on page load

axios.get('utils/api.php?q=getLoggedUser').then(r => {
    logged = r.data.idUser;
    let iduser = (typeof id === 'undefined') ? logged : id;
    axios.get('utils/api.php?q=getUserPosts&id='+iduser).then(r2 => {
        console.log(r2.data);
        r2.data.forEach(element => {
            arrayPost.push(element.IDpost);
        });
        canPrintPost = false;
        loadPosts(5);
    });
});
