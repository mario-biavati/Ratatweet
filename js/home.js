var arrayPost = [];
var cur_lastPost = 0;
var canPrintPost = true;

var main = document.getElementsByTagName("main")[0];

document.addEventListener("scroll", () => reloadFeed());

var searchBars = document.querySelectorAll("input[type=search]");

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
        main.innerHTML += htmlContent;
    });
}

function loadPosts(n_post) {
    for (let i = 0; i < n_post; i++) {

        if (i + cur_lastPost >= arrayPost.length) {
            if (searchText == '') {
                feed(n_post - i);
            } else {
                search(n_post - i);
            }
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
function reloadFeed() {
    if (isAtBottom() && canPrintPost) {
        canPrintPost = false;
        loadPosts(5);
    }
}

function resetFeed() {
    cur_lastPost = 0;
    main.innerHTML = "";
}

function search(n_post) {
    axios.get('utils/api.php?q=search&text='+searchText).then(r2 => {

        arrayPost = [];
        cur_lastPost = 0;
        r2.data.forEach(element => {
            arrayPost.push(element.IDpost);
        });
        loadPosts(n_post);
    });
}
function feed(n_post) {
    axios.get('utils/api.php?q=getFeedPosts&offset=0').then(r2 => {

        arrayPost = [];
        cur_lastPost = 0;
        r2.data.forEach(element => {
            arrayPost.push(element.IDpost);
        });
        loadPosts(n_post);
    });
}

function saveRecipe(IDrecipe) {
    const formData = new FormData();
    formData.append('q', "saveRecipe");
    formData.append('id', IDrecipe);
    axios.post('utils/api.php', formData);
}

// on page load

if (searchText == '') {
    feed(5);
} else {
    searchBars.forEach(bar => {
        bar.setAttribute("value", searchText);
    });
    search(5);
}