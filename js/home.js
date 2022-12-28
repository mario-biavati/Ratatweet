var idUser = -1;
var arrayPostFeed = [];
var arrayPostSearch = [];
var curArray = arrayPostFeed;
var cur_lastPost = 0;
var canPrintPost = true;

var main = document.getElementsByTagName("main")[0];

document.addEventListener("scroll", () => reloadFeed());

var searchBar = document.getElementById("searchBarLG");
searchBar.addEventListener("input", () => search(5));

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

function loadPosts(n_post, array) {
    for (let i = 0; i < n_post; i++) {
        printPost(array[i + cur_lastPost]);
    }
    cur_lastPost += n_post;
    canPrintPost = true;
}
function isAtBottom() {
    return document.documentElement.clientHeight + window.scrollY >= (document.documentElement.scrollHeight);
}
function reloadFeed() {
    if (isAtBottom() && canPrintPost) {
        canPrintPost = false;
        loadPosts(5, curArray);
    }
}

function resetFeed() {
    cur_lastPost = 0;
    main.innerHTML = "";
}

function search(n_post) {
    let text = String(searchBar.value);
    axios.get('utils/api.php?q=search&text='+text.replace(/ /g,"+")).then(r2 => {

        arrayPostSearch = [];
        r2.data.forEach(element => {
            arrayPostSearch.push(element.IDpost);
        });
        resetFeed();
        curArray = arrayPostSearch;
        loadPosts(n_post, arrayPostSearch);
    });
}
function feed(n_post) {
    axios.get('utils/api.php?q=getFeedPosts&offset='+cur_lastPost).then(r2 => {

        r2.data.forEach(element => {
            arrayPostFeed.push(element.IDpost);
        });
        loadPosts(n_post, arrayPostFeed);
    });
}

function saveRecipe(IDrecipe) {
    axios.post('utils/api.php?q=saveRecipe&id='+IDrecipe);
}

// on page load
axios.get('utils/api.php?q=getLoggedUser').then(r1 => {

    idUser = r1.data.IDuser;

    feed(5);
});