
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
    followButton.className = "btn btn-secondary";
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
    followButton.className = "btn btn-info";
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

    notificationButton.className = "btn btn-info";
    notificationButton.alt="Disable notifications";
    notificationButton.setAttribute('onclick', 'disableNotifications()');
}
function disableNotifications() {
    const formData = new FormData();
    formData.append('q', "disableNotifications");
    formData.append('idFollowed', id);
    axios.post('utils/api.php', formData);

    notificationButton.className = "btn btn-secondary";
    notificationButton.alt="Enable notifications";
    notificationButton.setAttribute('onclick', 'enableNotifications()');
}

function printPost(idPost) {
    return new Promise((resolve) => {
        axios.get('utils/api.php?q=getPost&id=' + idPost).then(r => {
            let post = r.data;
            const rating = r.data.avgRating;
            let htmlContent = `
            <article id="${post.IDpost}">
            <a href="post.php?id=${post.IDpost}" class="d-flex" style="margin-bottom: 10px; text-decoration: none; color: black;">
                <picture>
                    <img src="data:image/png;base64,${post.pic}" style="max-width: 100px;max-height: 100px;" />
                </picture>
                <div style="padding-left:7px;">
                    <h3 style="padding-top: 0px;padding-left: 0px;">${post.title}</h3>
                    <div class="rating"> `;
            let i;
            for(i=5; i>rating; i--) htmlContent+="<span>☆</span>";
            if(rating!=0) htmlContent+=`<span class="ratingDisplay">☆</span>`;
            for(i=rating-1; i>0; i--) htmlContent+="<span>☆</span>";
            htmlContent+=`
                    </div>
                </div>
            </a>
            </article>
            `;
            //main.innerHTML += htmlContent;
            resolve(htmlContent);
        });
    });
}

async function loadPosts(n_post) {
    canPrintPost = false;
    let HTMLcontent = '';
    for (let i = 0; i < n_post; i++) {

        if (i + cur_lastPost >= arrayPost.length) {
            cur_lastPost += i;
            posts.innerHTML += HTMLcontent;
            return;
        }
        HTMLcontent += await printPost(arrayPost[i + cur_lastPost]);
    }
    posts.innerHTML += HTMLcontent;
    cur_lastPost = cur_lastPost + n_post;
    canPrintPost = true;
}
function isAtBottom() {
    return document.documentElement.clientHeight + window.scrollY >= (document.documentElement.scrollHeight);
}
function reloadPosts() {
    if (isAtBottom() && canPrintPost) {
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

        loadPosts(5);
    });
});

function logout() {
    const formData = new FormData();
    formData.append('q', "logout");
    axios.post('utils/api.php', formData);
}

function modify(){
    location.href="registration_page.php";
}
