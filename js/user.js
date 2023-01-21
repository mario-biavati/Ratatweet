
var logged = -1;
var followButton = document.getElementById("followbutton");
var notificationButton = document.getElementById("notificationbutton");

var posts = document.getElementById("posts");
var followers = document.getElementById("followers");
var followed = document.getElementById("followed");
var arrayPost = [];
var arrayFollowers = [];
var arrayFollowed = [];
var cur_lastPost = 0;
var cur_lastFollowers = 0;
var cur_lastFollowed = 0;
var canPrintPost = true;
var canPrintFollowers = true;
var canPrintFollowed = true;
var currentDiv = "posts";

document.addEventListener("scroll", () => reload());

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
    followButton.classList.replace("btn-info", "btn-secondary");
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
    followButton.classList.replace("btn-secondary", "btn-info");
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

    notificationButton.classList.replace("btn-secondary", "btn-info");
    notificationButton.alt="Disable notifications";
    notificationButton.setAttribute('onclick', 'disableNotifications()');
}
function disableNotifications() {
    const formData = new FormData();
    formData.append('q', "disableNotifications");
    formData.append('idFollowed', id);
    axios.post('utils/api.php', formData);

    notificationButton.classList.replace("btn-info", "btn-secondary");
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
function printFollower(idUser, i) {
    return new Promise((resolve) => {
        axios.get('utils/api.php?q=getUserInfo&idUser=' + idUser).then(r => {
            let userData = r.data;
            let userHref = "user_page.php?id="+idUser;
            let htmlContent = "";
            if(i==0) htmlContent = `<ul class="list-group">`;
            htmlContent+= `
            <li class="list-group-item mx-auto col-10 col-md-9 col-lg-8">
                <img src="data:image/png;base64,${userData["pic"]}" alt="${userData["username"]}_Pic" width = "50px" height = "50px" />
                <a href="${userHref}">${userData["username"]}</a>   
            </li> `;
            if(i==arrayFollowers.length) htmlContent += `</ul>`;
            resolve(htmlContent);
        });
    });
}
function printFollowed(idUser, i) {
    return new Promise((resolve) => {
        axios.get('utils/api.php?q=getUserInfo&idUser=' + idUser).then(r => {
            let userData = r.data;
            let userHref = "user_page.php?id="+idUser;
            let htmlContent = "";
            if(i==0) htmlContent = `<ul class="list-group">`;
            htmlContent+= `
            <li class="list-group-item mx-auto col-10 col-md-9 col-lg-8">
                <img src="data:image/png;base64,${userData["pic"]}" alt="${userData["username"]}_Pic" width = "50px" height = "50px" />
                <a href="${userHref}">${userData["username"]}</a>   
            </li> `;
            if(i==arrayFollowed.length) htmlContent += `</ul>`;
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
async function loadFollowers(n_followers) {
    canPrintFollowers = false;
    let HTMLcontent = '';
    for (let i = 0; i < n_followers; i++) {

        if (i + cur_lastFollowers >= arrayFollowers.length) {
            cur_lastFollowers += i;
            followers.innerHTML += HTMLcontent;
            return;
        }
        HTMLcontent += await printFollower(arrayFollowers[i + cur_lastFollowers], i + cur_lastFollowers);
    }
    followers.innerHTML += HTMLcontent;
    cur_lastFollowers = cur_lastFollowers + n_followers;
    canPrintFollowers = true;
}
async function loadFollowed(n_followed) {
    canPrintFollowed = false;
    let HTMLcontent = '';
    for (let i = 0; i < n_followed; i++) {

        if (i + cur_lastFollowed >= arrayFollowed.length) {
            cur_lastFollowed += i;
            followed.innerHTML += HTMLcontent;
            return;
        }
        HTMLcontent += await printFollowed(arrayFollowed[i + cur_lastPost], i + cur_lastFollowed);
    }
    followed.innerHTML += HTMLcontent;
    cur_lastFollowed = cur_lastFollowed + n_followed;
    canPrintFollowed = true;
}
function isAtBottom() {
    return document.documentElement.clientHeight + window.scrollY >= (document.documentElement.scrollHeight);
}
function reload() {
    if (isAtBottom()) {
        if(currentDiv="posts" && canPrintPost) loadPosts(5);
        else if(currentDiv="followers" && canPrintFollowers) loadFollowers(5);
        else if(currentDiv="followed" && canPrintFollowed) loadFollowed(5);
    }
}

//ON PAGE LOAD
let IDuser = (typeof id === 'undefined') ? -1 : id;
//Get user posts
axios.get('utils/api.php?q=getLoggedUser').then(r => {
    logged = r.data.idUser;
    let iduser = (typeof id === 'undefined') ? logged : id;
    axios.get('utils/api.php?q=getUserPosts&id='+iduser).then(r2 => {
        console.log(r2.data);
        r2.data.forEach(element => {
            arrayPost.push(element.IDpost);
        });
    });
    if(arrayPost.length==0) {
        posts.innerHTML=`<p class="fs-2 text-muted text-center p-5 m-0">No posts</p>`;
    }
});
//Get user followers list
axios.get('utils/api.php?q=getUserFollowers&idUser='+IDuser).then(r => {
        console.log(r.data);
        r.data.forEach(element => {
            arrayFollowers.push(element.IDuser);
        });
        if(arrayFollowers.length==0) {
            followers.innerHTML=`<p class="fs-2 text-muted text-center p-5 m-0">No followers</p>`;
        }
});
//Get user followed list
axios.get('utils/api.php?q=getUserFollowed&idUser='+IDuser).then(r => {
    console.log(r.data);
    r.data.forEach(element => {
        arrayFollowed.push(element.IDuser);
    });
    if(arrayFollowed.length==0) {
        followed.innerHTML=`<p class="fs-2 text-muted text-center p-5 m-0">No followed</p>`;
    }
});

function logout() {
    const formData = new FormData();
    formData.append('q', "logout");
    axios.post('utils/api.php', formData);
}

function modify(){
    location.href="registration_page.php";
}


let navButtons = document.querySelectorAll("main ul.nav>li"); /* 0: Posts, 1: Followers, 2: Followed, 3: AvgRating */

navButtons[0].addEventListener("click", onPostsClick);
function onPostsClick() {
    navButtons[0].classList.add('border-bottom', 'border-info', 'border-4');
    navButtons[1].classList.remove('border-bottom', 'border-info', 'border-4');
    navButtons[2].classList.remove('border-bottom', 'border-info', 'border-4');
    currentDiv="posts";
    loadPosts(5);
}

navButtons[1].addEventListener("click", onFollowersClick);
function onFollowersClick() {
    navButtons[1].classList.add('border-bottom', 'border-info', 'border-4');
    navButtons[0].classList.remove('border-bottom', 'border-info', 'border-4');
    navButtons[2].classList.remove('border-bottom', 'border-info', 'border-4');
    currentDiv="followers";
    loadFollowers(5);
}

navButtons[2].addEventListener("click", onFollowedClick);
function onFollowedClick() {
    navButtons[2].classList.add('border-bottom', 'border-info', 'border-4');
    navButtons[1].classList.remove('border-bottom', 'border-info', 'border-4');
    navButtons[0].classList.remove('border-bottom', 'border-info', 'border-4');
    currentDiv="followed";
    loadFollowed(5);
}

//on page load
onPostsClick();